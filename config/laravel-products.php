<?php

return [
    'routes_enabled' => true,
    'PER_PAGE' => env('PER_PAGE') ?? 15,
    'products' => [
        'table' => 'products',
        'model' => \Wovosoft\LaravelProducts\Models\Products::class,
        'fillable' => ['*'],
        'types' => [
            'countable',
            'uncountable'
        ],
        'default_type' => 'countable',
        'units' => [
            'Kilogram',
            'Quantity',
            'Liter'
        ],
        'default_unit' => 'Quantity',
        'attributes' => [
            'table' => 'product_attributes',
            'model' => \Wovosoft\LaravelProducts\Models\ProductAttributes::class,
            'fillable' => ['*'],
        ],
        'variations' => [
            'table' => 'product_variations',
            'model' => \Wovosoft\LaravelProducts\Models\ProductVariations::class,
            'fillable' => ['*']
        ],
    ],
    'categories' => [
        'table' => 'product_categories',
        'model' => \Wovosoft\LaravelProducts\Models\ProductCategories::class,
        'fillable' => ['*'],
    ],
    'brands' => [
        'table' => 'product_brands',
        'model' => \Wovosoft\LaravelProducts\Models\ProductBrands::class,
        'fillable' => ['*'],
    ],
    'assignments' => [
        'products_brands' => [
            'table' => 'products_brands_assignments',
            'model' => \Wovosoft\LaravelProducts\Models\ProductsBrandsAssignments::class,
        ],
        'products_categories' => [
            'table' => 'products_categories_assignments',
            'model' => \Wovosoft\LaravelProducts\Models\ProductsCategoriesAssignments::class
        ]
    ],
    'resource_path' => resource_path('wovosoft/laravel-products')
];
