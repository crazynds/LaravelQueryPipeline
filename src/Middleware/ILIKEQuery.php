<?php

namespace Crazynds\QueryPipeline\Middleware;

use Illuminate\Support\Arr;

class ILIKEQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $config)
    {
        $or = ($data['or'] ?? false) ? true : false;

        foreach ($config as $name => $columns) {
            $tablename = $this->getTableName($name);
            $columnsData = array_map(function ($key, $val) {
                return $val;
            }, array_keys($columns), $columns);
            $columnsInv = array_flip($columns);

            $values = Arr::only($data, $columnsData);

            array_map(function ($key, $value) use ($query, $tablename, $columnsInv, $or) {
                if (isset($columnsInv[$key]) && gettype($columnsInv[$key]) == 'string') {
                    $tableCol = $columnsInv[$key];
                } else {
                    $tableCol = $key;
                }
                if ($or) {
                    if (config('database.default') == 'pgsql') {
                        $query->orWhere($tablename.'.'.$tableCol, 'ILIKE', '%'.$value.'%');
                    } else {
                        $query->orWhereRaw('LOWER(`'.$tablename.'`.`'.$tableCol.'`) LIKE  \'%'.strtolower($value).'%\'');
                    }
                } else {
                    if (config('database.default') == 'pgsql') {
                        $query->where($tablename.'.'.$tableCol, 'ILIKE', '%'.$value.'%');
                    } else {
                        $query->whereRaw('LOWER(`'.$tablename.'`.`'.$tableCol.'`) LIKE  \'%'.strtolower($value).'%\'');
                    }
                }
            }, array_keys($values), $values);
        }

        return $query;
    }
}
