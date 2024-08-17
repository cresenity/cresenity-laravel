<?php

namespace Cresenity\Laravel\CElement\Element\FormInput\QueryBuilder;

use Illuminate\Contracts\Support\Arrayable;

class FilterBuilder implements Arrayable
{
    protected $filters;

    public function __construct()
    {
        $this->filters = [];
    }

    /**
     * @param null|string $id
     *
     * @return \Cresenity\Laravel\CElement\Element\FormInput\QueryBuilder\Filter
     */
    public function addFilter($id = null)
    {
        $filter = new Filter($id);

        $this->filters[] = $filter;

        return $filter;
    }

    public function withFilter($callback)
    {
        \c::tap($this->addFilter(), $callback);

        return $this;
    }

    public function toArray()
    {
        return \c::collect($this->filters)->map(function ($filter) {
            return $filter instanceof Arrayable ? $filter->toArray() : $filter;
        })->toArray();
    }
}
