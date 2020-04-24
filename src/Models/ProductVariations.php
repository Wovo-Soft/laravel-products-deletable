<?php

namespace Wovosoft\LaravelProducts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariations extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('laravel-products.products.variations.table');

        if (config('laravel-products.products.variations.fillable') == ['*']) {
            $this->guarded = ['id'];
        } else {
            $this->fillable = config('laravel-products.products.variations.fillable');
        }
        //NOTE: Call this constructor after initializing dynamic values
        parent::__construct($attributes);
    }

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(config('laravel-products.products.model'), 'id', 'product_id');
    }
}
