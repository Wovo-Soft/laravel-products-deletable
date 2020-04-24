<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Faker\Generator as Faker;

$factory->define(config('laravel-products.brands.model'), function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text(150)
    ];
});
