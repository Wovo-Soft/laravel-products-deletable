<?php

namespace Wovosoft\LaravelProducts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductsBrandsAssignments extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('laravel-products.assignments.products_brands.table');
        $this->guarded = ['id'];
    }

    /**
     * @return HasOne
     */
    public function brand(): HasOne
    {
        return $this->hasOne(config('laravel-products.assignments.products_brands.model'), 'id', 'brand_id');
    }

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(config('laravel-products.products.model'), 'id', 'product_id');
    }
}
