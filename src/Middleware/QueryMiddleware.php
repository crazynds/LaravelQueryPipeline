<?php

namespace Crazynds\QueryPipeline\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

abstract class QueryMiddleware
{
    public function handle(array $data, Closure $next, ...$params)
    {
        $query = $next($data);
        $config = $this->uncodeParams($params);

        $this->apply($query, $data, $config);

        return $query;
    }

    abstract protected function apply(Builder $query, array $data, $config);

    private function uncodeParams($params)
    {
        $newParams = [];
        $patterns = [
            '/§/i',
            '/º/i',
        ];
        $replacement = [
            ':',
            ',',
        ];
        foreach ($params as $param) {
            $exploded = explode(':', $param, 2);
            if (count($exploded) == 2) {
                $newParams[$exploded[0]] = $this->safeUnserialize(preg_replace($patterns, $replacement, $exploded[1]));
            } else {
                $newParams[] = $this->safeUnserialize(preg_replace($patterns, $replacement, $param));
            }
        }

        return $newParams;
    }

    /**
     * Unserialize a value encoded by the pipeline, falling back to the raw
     * string when it isn't valid serialized data.
     *
     * unserialize() only raises a warning (not a Throwable) on malformed
     * input. Whether that warning is promoted to a catchable exception
     * depends on the error handler active at call time, which is not
     * guaranteed inside long-running workers (e.g. Laravel Octane). Checking
     * the return value directly works regardless of the error handler.
     */
    private function safeUnserialize(string $value)
    {
        $result = @unserialize($value);

        if ($result === false && $value !== serialize(false)) {
            return $value;
        }

        return $result;
    }

    public function getTableName($name)
    {
        if (is_subclass_of($name, Model::class)) {
            return (new $name)->getTable();
        } else {
            return $name;
        }
    }

    public function getDriverName()
    {
        return DB::getDriverName();
    }
}
