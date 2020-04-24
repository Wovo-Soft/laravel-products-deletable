<?php

namespace Wovosoft\LaravelProducts\Builders;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class QueryBuilder
{
    private Builder $builder;

    /**
     * QueryBuilder constructor.
     * @param string | Builder $builder Model Class or Query Builder Instance
     */
    public function __construct($builder)
    {
        $this->builder = ($builder instanceof Builder) ? $builder : $builder::query();
    }

    /**
     * This is a magic method implementation
     * @param string $method Magic Method to be called in builder directly
     * @param $arguments
     * @return $this
     */
    public function __call($method, $arguments): QueryBuilder
    {
        $this->builder->$method(...$arguments);
        return $this;       //making it chain-able
    }

    /**
     * @param string $method Method to be called in builder directly
     * @param $arguments
     * @return $this
     */
    public function call($method, $arguments = [])
    {
        $this->builder->$method(...$arguments);
        return $this;
    }

    /**
     * Set / Change the Builder instance
     * @param Builder $builder
     * @return self
     */
    public function setBuilder(Builder $builder): QueryBuilder
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * @return Builder
     */
    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * @param string|null $filter Query String
     * @param array $columns Columns to be queried
     * @param string $filter_type and | or
     * @return $this
     */
    public function filter(string $filter = null, array $columns = [], string $filter_type = "or"): QueryBuilder
    {
        if ($filter && is_array($columns) && count($columns)) {
            if ($columns === ['*']) {
                $columns = $this->builder->getConnection()->getSchemaBuilder()->getColumnListing($this->builder->getModel()->getTable());
            }
            if (trim($filter_type) === "or") {
                for ($x = 0; $x < count($columns); $x++) {
                    if ($x === 0) {
                        $this->builder->where($columns[$x], "LIKE", "%" . trim($filter) . "%");
                    } else {
                        $this->builder->orWhere($columns[$x], "LIKE", "%" . trim($filter) . "%");
                    }
                }
            } elseif (trim($filter_type) === 'and') {
                foreach ($columns as $column) {
                    $this->builder->where($column, "LIKE", "%" . trim($filter) . "%");
                }
            }
        }
        return $this;
    }

    /**
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function datatable(array $options = []): LengthAwarePaginator
    {
        $options = array_merge([
            'orderBy' => 'id',
            'order' => 'desc',
            'filter' => null,
            'filter_type' => 'or',
            'columns' => ['*'],
            'per_page' => config('laravel-products.PER_PAGE')
        ], Arr::where($options, function ($v, $k) {
            return !!$v;
        }));

        if ($options["filter"] && $options["columns"]) {
            $this->filter($options["filter"], $options["columns"], $options["filter_type"]);
        }

        return $this
            ->builder
            ->orderBy($options["orderBy"], $options["order"])
            ->paginate($options['per_page']);
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function defaultDatatable(Request $request): LengthAwarePaginator
    {
        return $this
            ->select(['*'])
            ->datatable([
                "filter" => $request->post("filter"),
                "filter_type" => $request->post("filter_type"),
                "per_page" => $request->post("per_page"),
                "orderBy" => $request->post("orderBy"),
                "order" => $request->post("order"),
                "columns" => $request->post('columns') ?? ['*']
            ]);
    }
}
