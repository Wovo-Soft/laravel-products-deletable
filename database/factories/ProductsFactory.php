<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(config('laravel-products.products.model'), function (Faker $faker) {
    return [
        'name' => $faker->name,
        'upc' => uniqid(),
        'type' => config('laravel-products.product_types')[random_int(0, count(config('laravel-products.product_types')) - 1)],
        'unit' => config('laravel-products.product_units')[random_int(0, count(config('laravel-products.product_units')) - 1)],
//        'image' => $faker->imageUrl(),
        'image' => 'https://picsum.photos/400/600/?image=' . random_int(500, 1000),
        'cost' => $faker->randomFloat(2, 50, 150),
        'price' => $faker->randomFloat(2, 50, 150),
        'description' => $faker->text(150)
    ];
});
