<?php

namespace Wovosoft\LaravelProducts\Seeds;

use Illuminate\Database\Seeder;
use Wovosoft\LaravelProducts\Models\ProductBrands;
use Wovosoft\LaravelProducts\Models\ProductCategories;
use Wovosoft\LaravelProducts\Models\Products;
use Wovosoft\LaravelProducts\Models\ProductsBrandsAssignments;
use Wovosoft\LaravelProducts\Models\ProductsCategoriesAssignments;

class ProductsSeeder extends Seeder
{
    const NUMBER_OF_ITEMS = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $categories = factory(config('laravel-products.categories.model'), self::NUMBER_OF_ITEMS)
            ->create()
            ->pluck('id');
        $brands = factory(config('laravel-products.brands.model'), self::NUMBER_OF_ITEMS)
            ->create()
            ->pluck('id');
        $products = factory(config('laravel-products.products.model'), self::NUMBER_OF_ITEMS)
            ->create()
            ->pluck('id');
        for ($x = 0; $x < self::NUMBER_OF_ITEMS; $x++) {
            config('laravel-products.assignments.products_brands.model')::create([
                'product_id' => $products[random_int(0, self::NUMBER_OF_ITEMS - 1)],
                'brand_id' => $brands[random_int(0, self::NUMBER_OF_ITEMS - 1)],
            ]);
            config('laravel-products.assignments.products_categories.model')::create([
                'product_id' => $products[random_int(0, self::NUMBER_OF_ITEMS - 1)],
                'category_id' => $categories[random_int(0, self::NUMBER_OF_ITEMS - 1)],
            ]);
        }
    }
}
