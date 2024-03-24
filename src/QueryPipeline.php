<?php

namespace Crazynds\QueryPipeline;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

trait QueryPipeline
{
    private function reduceArray($array)
    {
        $carry = '';
        foreach ($array as $key => $valor) {
            if (gettype($valor) == 'array') {
                $valor = serialize($valor);
            }
            $carry = (empty($carry) ? '' : ($carry.',')).(gettype($key) == 'string' ? ($key.':') : '').str_replace([',', ':'], ['º', '§'], $valor);
        }

        return $carry;
    }

    private function getFormatedMiddlewares($stack)
    {
        $newStack = [];
        foreach ($stack as $key => $value) {
            if (gettype($key) == 'string') {
                if (gettype($value) == 'string') {
                    $newStack[] = $key.':'.$value;
                } elseif (gettype($value) == 'array' && count($value) > 0) {
                    $reduced = $this->reduceArray($value);
                    $newStack[] = $key.':'.$reduced;
                } else {
                    $newStack[] = $key;
                }
            } else {
                $newStack[] = $value;
            }
        }

        return $newStack;
    }

    public function runPipeline(Builder $oringinalQuery, array $data, array $stackMiddleware)
    {
        $newStack = $this->getFormatedMiddlewares($stackMiddleware);
        $oringinalQuery->where(function ($query) use ($data, $newStack, $oringinalQuery) {
            $query = app(Pipeline::class)
                ->send($data)
                ->through(array_reverse($newStack))
                ->then(function () use ($query) {
                    return $query;
                });
            $wheres = $query->getQuery()->wheres;
            $query->getQuery()->wheres = [];
            $query->getQuery()->bindings['where'] = [];
            $query->where(function ($query) use ($wheres) {
                foreach ($wheres as $where) {
                    $boolean = 'or';
                    if ($where['boolean'] == $boolean) {
                        if ($where['type'] == 'Exists') {
                            $query->addWhereExistsQuery($where['query'], $boolean, $where['not'] ?? false);
                        } elseif ($where['type'] == 'Nested') {
                            $query->addNestedWhereQuery($where['query'], $boolean);
                        } elseif ($where['type'] == 'In' || $where['type'] == 'NotIn') {
                            $query->whereIn($where['column'], $where['values'], $boolean, $where['type'] == 'NotIn');
                        } else {
                            $query->where($where['column'], $where['operator'], $where['value'], $boolean);
                        }
                    }
                }
            });
            $query->where(function ($query) use ($wheres) {
                foreach ($wheres as $where) {
                    $boolean = 'and';
                    if ($where['boolean'] == $boolean) {
                        if ($where['type'] == 'Exists') {
                            $query->addWhereExistsQuery($where['query'], $boolean, $where['not'] ?? false);
                        } elseif ($where['type'] == 'Nested') {
                            $query->addNestedWhereQuery($where['query'], $boolean);
                        } elseif ($where['type'] == 'In' || $where['type'] == 'NotIn') {
                            $query->whereIn($where['column'], $where['values'], $boolean, $where['type'] == 'NotIn');
                        } else {
                            $query->where($where['column'], $where['operator'], $where['value'], $boolean);
                        }
                    }
                }
            });

            $oringinalQuery->getQuery()->bindings['order'] = $query->getQuery()->bindings['order'];
            $oringinalQuery->getQuery()->orders = $query->getQuery()->orders;
        });

        return $oringinalQuery;
    }
}
