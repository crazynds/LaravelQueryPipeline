<?php

namespace Crazynds\QueryPipeline\Middleware;

use Crazynds\QueryPipeline\Middleware\QueryMiddleware;

class OrderByQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $params)
    {
        $def = true;
        $desc = ($data['sortDesc'] ?? false) ? 'desc' : 'asc';
        if(isset($data['sortBy'])){
            $column = $data['sortBy'];
            foreach ($params as $name => $columns) {
                $tablename = $this->getTableName($name);
                if(in_array($column,$columns)){
                    $query->orderBy($tablename.'.'.$column,$desc);
                    $def = false;
                }
            }
        }
        if($def && isset($params['default'])){
            if(is_array($params['default'])){
                foreach($params['default'] as $order){
                    $query->orderBy($order, $desc);
                }
            }else $query->orderBy($params['default'], $desc);
        }
    }
}
