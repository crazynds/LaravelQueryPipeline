<?php

namespace Crazynds\QueryPipeline\Middleware;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class BetweenDatesQuery extends QueryMiddleware
{
    public function apply($query, array $data, $params)
    {
        $or = ($data['or'] ?? false) ? true : false;
        foreach ($params as $name => $pairs) {
            $tablename = $this->getTableName($name);
            foreach ($pairs as $key => $pair) {
                unset($arr,$minB,$minA,$maxA,$maxB);
                if (gettype($key) == 'string') {
                    $arr = explode('|', $key);
                    $arr2 = explode('|', $pair);
                    if (count($arr) == 1) {
                        $minB = 'min_'.$arr2[0];
                        $maxB = 'max_'.$arr2[0];
                    } else {
                        $minB = $arr2[0];
                        $maxB = $arr2[1];
                    }
                } else {
                    $arr = explode('|', $pair);
                }
                if (count($arr) == 1) {
                    $arr = [$arr[0], $arr[0]];
                }
                if (count($arr) != 2) {
                    continue;
                }
                $minA = $tablename.'.'.$arr[0];
                $maxA = $tablename.'.'.$arr[1];
                if (! isset($minB) || ! isset($maxB)) {
                    $minB = 'min_'.$arr[0];
                    $maxB = 'max_'.$arr[1];
                }
                if (! Arr::has($data, $minB)) {
                    continue;
                }

                try {
                    $minB = Carbon::parse($data[$minB])->toIso8601String();
                    $maxB = Arr::get($data, $maxB, null);
                    if (isset($maxB)) {
                        if (strlen($maxB) <= 10) {
                            $maxB = Carbon::parse($maxB)->endOfDay();
                        } else {
                            $maxB = Carbon::parse($maxB);
                        }
                        $maxB = $maxB->toIso8601String();
                    }
                } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                    continue;
                }
                $closure = function ($query) use ($minA, $maxA, $minB, $maxB) {
                    if (! isset($maxB)) {
                        $query->where(function ($query) use ($maxA, $minB) {
                            $query->where($maxA, '>=', $minB)
                                ->whereNotNull($maxA);
                        })->orWhereNull($maxA);
                    } else {
                        $query->where($minA, '<=', $maxB)
                            ->where(function ($query) use ($maxA, $minB) {
                                $query->whereNull($maxA)
                                    ->orWhere($maxA, '>=', $minB);
                            });
                    }
                };
                if ($or) {
                    $query->orWhere($closure);
                } else {
                    $query->where($closure);
                }
            }
        }
    }
}
