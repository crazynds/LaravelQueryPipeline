<?php

namespace Crazynds\QueryPipeline\Middleware;

use Crazynds\QueryPipeline\Middleware\QueryMiddleware;

class OrderByQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $params)
    {
        $def = true;
        if(isset($data['sortBy'])){
            $desc = ($data['sortDesc'] ?? false) ? 'desc' : 'asc';
            $column = $data['sortBy'];
            foreach ($params as $name => $columns) {
                $tablename = $this->getTableName($name);
                if (in_array($column, $columns)) {
                    $query->orderBy($tablename.'.'.$column, $desc);
                    $def = false;
                }
            }
        }
        if($def && isset($params['default'])){
            $query->orderBy($params['default']);
        }
    }
}
