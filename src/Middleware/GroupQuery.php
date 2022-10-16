<?php

namespace Crazynds\QueryPipeline\Middleware;

use Crazynds\QueryPipeline\QueryPipeline;

class GroupQuery extends QueryMiddleware
{
    use QueryPipeline;

    protected function apply($query, array $data, $params)
    {
        $or = ($data['or'] ?? false) ? true : false;

        unset($data['or']);

        $callback = function ($query) use ($data, $params) {
            $this->runPipeline($query, $data, $params);
        };

        if ($or) {
            $query->orWhere($callback);
        } else {
            $query->where($callback);
        }
    }
}
