<?php

namespace App\Modules\Client;

use Crazynds\QueryPipeline\Middleware\QueryMiddleware;
use Illuminate\Database\Query\Builder;

class OrderByQuery extends QueryMiddleware
{
    protected function apply(Builder $query, array $data, $params)
    {
        $def = true;
        if (! isset($data['sortBy'])) {
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
        if ($def && isset($data['default'])) {
            $query->orderBy($data['default']);
        }
    }
}
