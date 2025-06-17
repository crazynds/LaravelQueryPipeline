<?php

namespace Crazynds\QueryPipeline\Middleware;

class StringQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $params)
    {
        $or = ($data['or'] ?? false) ? true : false;
        foreach ($params as $name => $columns) {
            $tablename = $this->getTableName($name);
            foreach ($columns as $column) {
                if (isset($data[$column])) {
                    $value = $data[$column];
                    if ($or) {
                        $query->orWhere($tablename.'.'.$column, $value);
                    } else {
                        $query->where($tablename.'.'.$column, $value);
                    }
                }
            }
        }
    }
}
