<?php

namespace Crazynds\QueryPipeline\Middleware;

class SearchQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $config)
    {
        if (! isset($data['search'])) {
            return $query;
        }
        $columnsToSearch = [];
        foreach ($config as $name => $columns) {
            $tablename = $this->getTableName($name);

            foreach ($columns as $column) {
                $columnsToSearch[] = "$tablename.$column";
            }
        }
        if ($columnsToSearch) {
            if ($this->getDriverName() == 'pgsql') {
                $query->whereAny($columnsToSearch, 'ilike', '%'.$data['search'].'%');
            } else {
                $query->whereAny($columnsToSearch, 'like', '%'.$data['search'].'%');
            }

        }

        return $query;
    }
}
