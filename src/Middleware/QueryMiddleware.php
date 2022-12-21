<?php

namespace Crazynds\QueryPipeline\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Throwable;

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
                try{
                    $newParams[$exploded[0]] = unserialize(preg_replace($patterns, $replacement, $exploded[1]));
                }catch(Throwable $t){
                    $newParams[$exploded[0]] = $exploded[1];
                }
            } else {
                try{
                    $newParams[] = unserialize(preg_replace($patterns, $replacement, $param));
                }catch(Throwable $t){
                    $newParams[] = $param;
                }
            }
        }

        return $newParams;
    }

    protected function getTableName($name)
    {
        if (is_subclass_of($name, Model::class)) {
            return (new $name())->getTable();
        } else {
            return $name;
        }
    }
}
