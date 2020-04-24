<?php

namespace Wovosoft\LaravelProducts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBrands extends Model
{
    use SoftDeletes;

    public function __construct(array $attributes = [])
    {
        $this->table = config('laravel-products.brands.table');
        if (config('laravel-products.brands.fillable') == ['*']) {
            $this->guarded = ['id'];
        } else {
            $this->fillable = config('laravel-products.brands.fillable');
        }
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();
        // When a brand is about to be deleted it's association with other Products should be deleted.
        // The Assignments should have events like deleted, deleting, so we need to delete those one by one.
        // Deleting all at once doesn't triggers deleted or deleting events.
        self::deleting(function ($item) {
            $item->productAssignments()->each(function ($assignment) {
                $assignment->delete();
            });
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            config('laravel-products.products.model'),   //output
            config('laravel-products.assignments.products_brands.model'),
            'brand_id',    //in assignments table
            'id',          //in categories table
            'id',            //in products table
            'product_id'   //in assignments table
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productAssignments(): HasMany
    {
        return $this->hasMany(config('laravel-products.assignments.products_brands.model'), 'brand_id', 'id');
    }
}
