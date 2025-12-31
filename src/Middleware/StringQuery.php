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
                    $query->where($tablename.'.'.$column, $value, boolean: $or ? 'or' : 'and');
                } elseif (isset($data['not_'.$column])) {
                    $value = $data['not_'.$column];
                    $query->where(function ($query) use ($tablename, $column, $value) {
                        $query->where($tablename.'.'.$column, '!=', $value);
                        $query->orWhereNull($tablename.'.'.$column);
                    }, boolean: $or ? 'or' : 'and');
                }
            }
        }
    }
}
