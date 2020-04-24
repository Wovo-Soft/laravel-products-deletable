<?php

namespace Wovosoft\LaravelProducts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;


class Products extends Model
{
    use SoftDeletes;

    public function __construct(array $attributes = [])
    {
        $this->table = config('laravel-products.products.table');
        if (config('laravel-products.products.fillable') == ['*']) {
            $this->guarded = ['*'];
        } else {
            $this->fillable = config('laravel-products.products.fillable');
        }
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();
        self::deleting(function ($item) {
            $item->categoryAssignments()->each(function ($assignment) {
                $assignment->delete();
            });
            $item->brandAssignments()->each(function ($assignment) {
                $assignment->delete();
            });
            $item->productAttributes()->each(function ($attr) {
                $attr->delete();
            });
            $item->variations()->each(function ($variation) {
                $variation->delete();
            });
        });
    }

    /**
     * @return HasMany
     */
    public function categoryAssignments(): HasMany
    {
        return $this->hasMany(config('laravel-products.assignments.products_categories.model'), 'product_id', 'id');
    }

    /**
     * @return HasManyThrough
     */
    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(
            config('laravel-products.categories.model'),   //output
            config('laravel-products.assignments.products_categories.model'),
            'product_id',    //in assignments table
            'id',          //in categories table
            'id',            //in products table
            'category_id'   //in assignments table
        );
    }

    /**
     * Set Categories for a certain Product
     * @param array $categories
     * @return bool true/false or Throws Exception. Calling functions should catch it.
     * @throws \Throwable
     */
    public function setCategories(array $categories): bool
    {
        try {
            $this->categoryAssignments()->delete();
            return $this->categoryAssignments()->insert($categories);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * Returns the Brands Associated with a product.
     * @return HasMany
     */
    public function brandAssignments(): HasMany
    {
        return $this->hasMany(config('laravel-products.assignments.products_brands.model'), 'product_id', 'id');
    }

    /**
     * Returns the list of Brands of a certain product
     * @return HasManyThrough
     */
    public function brands(): HasManyThrough
    {
        return $this->hasManyThrough(
            config('laravel-products.brands.model'),
            config('laravel-products.assignments.products_brands.model'),
            'product_id',
            'id',
            'id',
            'brand_id'
        );
    }

    /**
     * @param array $brands
     * @return bool
     * @throws \Throwable
     */
    public function setBrands(array $brands): bool
    {
        try {
            $this->brandAssignments()->delete();
            return $this->brandAssignments()->insert($brands);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }


    public function productAttributes()
    {
        return $this->hasMany(config('laravel-products.products.attributes.model'), 'product_id', 'id');
    }

    public function setProductAttributes(array $attrs)
    {
        try {
            $this->productAttributes()->delete();
            foreach ($attrs as $attr) {
                if (isset($attr['id'])) {
                    unset($attr['id']);
                }
                $items = $this->productAttributes()->create($attr);
                if (!$items) {
                    throw new \UnexpectedValueException('Unable to Save Product Attributes', 404);
                }
            }
            return true;
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    public function variations()
    {
        return $this->hasMany(config('laravel-products.products.variations.model'), 'product_id', 'id');
    }

    public function setVariations($variations = null)
    {
        try {
            if (!$variations) {
                return $this->variations()->each(function ($variation) {
                    $variation->delete();
                });
            }
            if (!is_array($variations)) {
                throw new \Exception('Parameter 1 ($variations) should be an array, ' . gettype($variations) . ' given.', 404);
            }
            $prev = $this->variations()->get();
            $now = [];
            foreach ($variations as $variation) {
                $id = null;
                if (isset($variation['id'])) {
                    $id = $variation['id'];
                    unset($variation['id']);
                }
                $now[] = $this->variations()->updateOrCreate(['id' => $id], $variation)->id;
            }
            //now delete the rest of the variations not listed in the current items.
            foreach ($prev as $p) {
                if (!in_array($p->id, $now)) {
                    $p->delete();
                }
            }

            return true;
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
