<?php

namespace Modules\Agenda\Database\Middleware;

use Crazynds\QueryPipeline\Middleware\QueryMiddleware;

class DatetimeQueryMiddleware extends QueryMiddleware
{
    public function apply($query, array $data, $params)
    {
        $operator = ($data['or'] ?? false) ? 'or' : 'and';
        $dbConnection = $this->getDriverName();

        foreach ($params as $tablename => $list) {
            $tablename = $this->getTableName($tablename);
            foreach ($list as $column) {
                if (isset($data['year_'.$column])) {
                    $year = $data['year_'.$column];
                    if ($dbConnection === 'pgsql') {
                        $query->where();
                        $query->whereRaw("EXTRACT(YEAR FROM $tablename.$column) = ?", [$year], $operator);
                    } else {
                        $query->whereRaw("YEAR($tablename.$column) = ?", [$year], $operator);
                    }
                }

                if (isset($data['month_'.$column])) {
                    $month = $data['month_'.$column];
                    if ($dbConnection === 'pgsql') {
                        $query->whereRaw("EXTRACT(MONTH FROM $tablename.$column) = ?", [$month], $operator);
                    } else {
                        $query->whereRaw("MONTH($tablename.$column) = ?", [$month], $operator);
                    }
                }

                if (isset($data['day_'.$column])) {
                    $day = $data['day_'.$column];
                    if ($dbConnection === 'pgsql') {
                        $query->whereRaw("EXTRACT(DAY FROM $tablename.$column) = ?", [$day], $operator);
                    } else {
                        $query->whereRaw("DAY($tablename.$column) = ?", [$day], $operator);
                    }
                }

                if (isset($data['min_'.$column]) && isset($data['max_'.$column])) {
                    // Filtro entre min e max (inclusivo)
                    $query->whereBetween("$tablename.$column", [$data['min_'.$column], $data['max_'.$column]], $operator);
                } elseif (isset($data['min_'.$column])) {
                    $query->where("$tablename.$column", '>=', $data['min_'.$column], $operator);
                } elseif (isset($data['max_'.$column])) {
                    $query->where("$tablename.$column", '<=', $data['max_'.$column], $operator);
                }
            }
        }
    }
}
