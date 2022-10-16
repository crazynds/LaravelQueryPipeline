<?php

namespace Crazynds\QueryPipeline\Middleware;

class NumberQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $params)
    {
        $or = ($data['or'] ?? false) ? true : false;
        foreach ($params as $tablename => $columns) {
            foreach ($columns as $column => $comparations) {
                if (gettype($comparations) != 'array') {
                    $column = $comparations;
                    $comparations = ['=', '>', '<'];
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
                    }
                    if ($baseName != null && isset($data[$baseName])) {
                        $value = $data[$baseName];
                        if ($or) {
                            $query->orWhere($tablename.'.'.$column, $comparator, $value);
                        } else {
                            $query->where($tablename.'.'.$column, $comparator, $value);
                        }
                    }
                }
            }
        }
    }
}
