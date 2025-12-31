<?php

namespace Crazynds\QueryPipeline\Middleware;

class NumberQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $params)
    {
        $or = ($data['or'] ?? false) ? true : false;
        foreach ($params as $name => $columns) {
            $tablename = $this->getTableName($name);
            foreach ($columns as $column => $comparations) {
                if (gettype($comparations) != 'array') {
                    $column = $comparations;
                    $comparations = ['=', '>', '<', '!='];
                }
                foreach ($comparations as $comparator) {
                    $baseName = null;
                    switch ($comparator) {
                        case '=':
                        case '==':
                            $baseName = 'equal_'.$column;
                            break;
                        case '>=':
                        case '>':
                            $baseName = 'min_'.$column;
                            break;
                        case '<=':
                        case '<':
                            $baseName = 'max_'.$column;
                            break;
                        case '!=':
                            $baseName = 'not_'.$column;
                    }
                    if ($baseName != null && isset($data[$baseName])) {
                        $value = $data[$baseName];
                        $query->where(function ($query) use ($tablename, $column, $comparator, $value) {
                            $query->where($tablename.'.'.$column, $comparator, $value);
                            if ($comparator == '!=') {
                                $query->orWhereNull($tablename.'.'.$column);
                            }
                        }, boolean: $or ? 'or' : 'and');
                    }
                }
            }
        }
    }
}
