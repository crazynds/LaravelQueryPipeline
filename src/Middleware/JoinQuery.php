<?php

namespace Crazynds\QueryPipeline\Middleware;

class JoinQuery extends QueryMiddleware
{
    protected function apply($query, array $data, $config)
    {
        foreach ($config as $tableName => $join) {
            if (! isset($join['on'])) {
                continue;
            }
            $conditionA = $join['on'][0];
            $comparator = $join['on'][1];
            $conditionB = $join['on'][2];

            if (isset($join['checkParameters'])) {
                $checkParameters = $join['checkParameters'];
            }
            if (isset($join['checkNumeric'])) {
                $checkNumeric = $join['checkNumeric'];
            }

            //join if:

            if (isset($checkParameters) || isset($checkNumeric)) {
                if (isset($checkParameters) && count(array_intersect($data, $checkParameters)) != 0) {
                    // check parameter were passed and found in data
                    $query->join($tableName, $conditionA, $comparator, $conditionB);
                } elseif (isset($checkNumeric) && gettype($checkNumeric) == 'array') {
                    // check number were passed and the type of checknumber is array
                    $numberColumns = [];
                    foreach ($checkNumeric as $column => $comparations) {
                        if (gettype($comparations) != 'array') {
                            $numberColumns[] = 'equal_'.$column;
                            $numberColumns[] = 'min_'.$column;
                            $numberColumns[] = 'max_'.$column;
                        } else {
                            foreach ($comparations as $compare) {
                                switch ($compare) {
                                    case '=':
                                    case '==':
                                        $numberColumns[] = 'equal_'.$column;
                                        break;
                                    case '>=':
                                    case '>':
                                        $numberColumns[] = 'min_'.$column;
                                        break;
                                    case '<=':
                                    case '<':
                                        $numberColumns[] = 'max_'.$column;
                                        break;
                                }
                            }
                        }
                    }
                    if (count(array_intersect($data, $numberColumns)) != 0) {
                        // find all combination and check any of then
                        $query->join($tableName, $conditionA, $comparator, $conditionB);
                    }
                }
            } else {
                // No check were passed, just add the join
                $query->join($tableName, $conditionA, $comparator, $conditionB);
            }
        }
    }
}
