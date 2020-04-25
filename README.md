# Laravel Products
[![Latest Stable Version](https://poser.pugx.org/wovosoft/laravel-products/v/stable)](https://packagist.org/packages/wovosoft/laravel-products)
[![Total Downloads](https://poser.pugx.org/wovosoft/laravel-products/downloads)](https://packagist.org/packages/wovosoft/laravel-products)
[![License](https://poser.pugx.org/wovosoft/laravel-products/license)](https://packagist.org/packages/wovosoft/laravel-products)
## Package description
This Package provides the features of "Products". Normally for applications like Point of Sale, Inventory Management System
Pharmacy Management System, Library Management System etc. , we need Products Models and Migrations along with Front-End.
So in this package, the most required features for Products are added. If the package doesn't meet the needs properly then
it can be customized easily to meet your needs.
> Simple Products Management Package with Attributes, Variations, Costs, Prices , Images and many more.

## Features

- Vue Components for each possible features .
- Components are reusable. So, the default layout can be modified according to the needs of your application.
- Currently, the front-end uses Bootstrap-Vue, but you can easily change its layout.
- The front-end vue components are packaged as an npm package. You can use it as a module for the bundlers eg. Webpack,
- Products Attributes.
- Products Variations
- Facility of extending the package.

## Installation

Install via composer

```bash
composer require wovosoft/laravel-products
```

### Publish Configuration File


1. Publish the configuration file.

    ```bash
    php artisan vendor:publish --provider="Wovosoft\LaravelProducts\ServiceProvider" --tag="config"
    ```

2. Publish the Vue Components. The Published components will be copied to `resources/laravel-products/permissions` folder. You need use the component in`app.js`

    ```bash
    php artisan vendor:publish --provider="Wovosoft\LaravelProducts\ServiceProvider" --tag="resources"
    ```

3. Publish the Migrations

    ```bash
    php artisan vendor:publish --provider="Wovosoft\LaravelProducts\ServiceProvider" --tag="migrations"
    ```

3. Publish the Seeds

    ```bash
    php artisan vendor:publish --provider="Wovosoft\LaravelProducts\ServiceProvider" --tag="seeds"
    ```

## Configuration

1. Check the published configuration file in `config/laravel-products.php` , before running migration command.
    If you need to extend the package, then you may need to disable to routes. You can do it by disabling routes_enabled property to false.

2. Now Make the required tables by running below command.

    ```bash
    php artisan migrate
    ```

   If you want to install demo data, then first you need to publish the seeds by step #3. If it is done, then
   run database seeding command. Don't forget to check your `database/seeds/DatabaseSeeder.php` file. `ProductsSeeder.php`
   file should be there, and you need to add this to the run method in `database/seeds/DatabaseSeeder.php`. Now, you
   can run the database seed command.

   ```bash
   php artisan db:seed
   ```
   Also, You can run both migration and seeding command at once, by running below command.
   ```bash
   php artisan migrate:seed
   ```

3. Go to `config/laravel-products.php` . Modify the configs if you need.

    ```php

    return [
        'routes' => [
               'enabled' => true,
               'namespace' => 'Wovosoft',
               'prefix' => 'backend',
               'middleware' => ['web', 'auth']
           ],
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
    ```

4.  The package adds the routes automatically prefixed by `backend`, so your other routes shouldn't
    be prefixed by `backend`. However, you can change it in `config/laravel-products.php` config file.
    To check the registered routes, run in your terminal from project's root,

    ```bash
    php artisan route:list
    ```

## API Documentation
The full featured api documentation is not yet available. This will come very soon. However, for now please
check the package's source. Everything is commented nicely. You can get the idea easily from the comments.

## Security

If you discover any security related issues, please email [narayanadhikary24@gmail.com](narayanadhikary24@gmail.com)
or create issue in [the Github Repository](https://github.com/wovosoft/laravel-products).

## Credits

- [Narayan Adhikary](https://github.com/narai420)
- [All contributors](https://github.com/wovosoft/laravel-products/graphs/contributors)

This package is bootstrapped with the help of
[wovosoft/crud](https://github.com/wovosoft/crud).

