<?php

namespace Crazynds\QueryPipeline\Middleware;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

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
                $newParams[$exploded[0]] = unserialize(preg_replace($patterns, $replacement, $exploded[1]));
            } else {
                $newParams[] = unserialize(preg_replace($patterns, $replacement, $param));
            }
        }

        return $newParams;
    }

    protected function getTableName($name){
        if(is_subclass_of($name,Model::class)){
            return (new $name())->getTable();
        }else
            return $name;
    }
}
