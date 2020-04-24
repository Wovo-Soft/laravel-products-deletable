<?php

namespace Wovosoft\LaravelProducts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductsCategoriesAssignments extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('laravel-products.assignments.products_categories.table');
        $this->guarded = ['id'];
    }

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(config('laravel-products.products.model'), 'id', 'product_id');
    }

    /**
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(config('laravel-products.categories.model'), 'id', 'category_id');
    }
}
