<?php

namespace Crazynds\QueryPipeline\Middleware;

use Crazynds\QueryPipeline\Middleware\QueryMiddleware;

class OrderByQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $params)
    {
        $def = true;
<<<<<<< HEAD
        if(isset($data['sortBy'])){
=======
        if (! isset($data['sortBy'])) {
>>>>>>> c92874ad3043e715b13d6f9e249b300d2c1719b6
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
<<<<<<< HEAD
        if($def && isset($params['default'])){
=======
        if ($def && isset($data['default'])) {
>>>>>>> c92874ad3043e715b13d6f9e249b300d2c1719b6
            $query->orderBy($data['default']);
        }
    }
}
