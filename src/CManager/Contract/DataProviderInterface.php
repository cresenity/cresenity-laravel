<?php
namespace Cresenity\Laravel\CManager\Contract;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Enumerable;

interface DataProviderInterface
{
    public function searchOr(array $searchData);

    public function searchAnd(array $searchData);

    public function sort(array $sortData);

    /**
     * Paginate the given query.
     *
     * @param int        $perPage
     * @param array      $columns
     * @param string     $pageName
     * @param null|int   $page
     * @param null|mixed $callback
     *
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null, $callback = null);

    /**
     * @return \Illuminate\Support\Enumerable
     */
    public function toEnumerable();

    public function aggregate($method, $column);
}
