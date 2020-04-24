<?php

namespace Wovosoft\LaravelProducts\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->table = config('laravel-products.products.attributes.table');
        if (config('laravel-products.products.attributes.fillable') == ['*']) {
            $this->guarded = ['id'];
        } else {
            $this->fillable = config('laravel-products.products.attributes.fillable');
        }
        //NOTE: Call this constructor after initializing dynamic values
        parent::__construct($attributes);
    }

    /**
     * The Associated Product of the attribute
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne(config('laravel-products.products.attributes.model'), 'id', 'product_id');
    }

    /**
     * @param string | array $value
     * @return string[]
     */
    public function getValueAttribute($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        return explode(',', $value);
    }

    /**
     *
     * @param string | array $value
     * @return void
     */
    public function setValueAttribute($value): void
    {
        if (is_string($value)) {
            $this->attributes['value'] = $value;
        } else {
            $this->attributes['value'] = implode(",", $value);
        }
    }
}
