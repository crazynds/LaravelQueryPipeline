<?php

namespace Crazynds\QueryPipeline\Middleware;

class ILIKEQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $config)
    {
        $or = ($data['or'] ?? false) ? true : false;

        foreach ($config as $name => $columns) {
            $tablename = $this->getTableName($name);

            foreach ($columns as $key => $column) {
                $tableCol = (is_string($key)) ? $key : $column;

                if (isset($data[$column])) {
                    $value = $data[$column];
                    if ($or) {
                        if ($this->getDriverName() == 'pgsql') {
                            $query->orWhere($tablename.'.'.$tableCol, 'ILIKE', '%'.$value.'%');
                        } else {
                            $query->orWhereRaw('LOWER(`'.$tablename.'`.`'.$tableCol.'`) LIKE  ?', ['%'.$value.'%']);
                        }
                    } else {
                        if ($this->getDriverName() == 'pgsql') {
                            $query->where($tablename.'.'.$tableCol, 'ILIKE', '%'.$value.'%');
                        } else {
                            $query->whereRaw('LOWER(`'.$tablename.'`.`'.$tableCol.'`) LIKE  ?', ['%'.$value.'%']);
                        }
                    }
                } elseif (isset($data['not_'.$column])) {
                    $value = $data['not_'.$column];

                    if ($or) {
                        $query->orWhere(function ($query) use ($tablename, $tableCol, $value) {
                            if ($this->getDriverName() == 'pgsql') {
                                $query->where($tablename.'.'.$tableCol, 'NOT ILIKE', '%'.$value.'%');
                            } else {
                                $query->whereRaw('LOWER(`'.$tablename.'`.`'.$tableCol.'`) NOT LIKE  ?', ['%'.$value.'%']);
                            }
                            $query->orWhereNull($tablename.'.'.$tableCol);
                        });
                    } else {
                        $query->where(function ($query) use ($tablename, $tableCol, $value) {
                            if ($this->getDriverName() == 'pgsql') {
                                $query->where($tablename.'.'.$tableCol, 'NOT ILIKE', '%'.$value.'%');
                            } else {
                                $query->whereRaw('LOWER(`'.$tablename.'`.`'.$tableCol.'`) NOT LIKE  ?', ['%'.$value.'%']);
                            }
                            $query->orWhereNull($tablename.'.'.$tableCol);
                        });
                    }

                }
            }
        }

        return $query;
    }
}
