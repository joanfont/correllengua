<?php

namespace App\Application\Commons\Query;

interface QueryBus
{
    /**
     * @template T
     *
     * @param Query<T> $query
     *
     * @return T The query result
     */
    public function query(Query $query);
}
