<?php

namespace Wovosoft\LaravelProducts;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/laravel-products.php';

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH => config_path('laravel-products.php'),
            ], 'config');
        }


        $this->loadMigrationsFrom(__DIR__ . "/../database/migrations");
        if (config('laravel-products.routes_enabled')) {
            $this->loadRoutesFrom(__DIR__ . "/routes.php");
        }
        $this->loadFactoriesFrom(__DIR__ . "/../database/factories");
        $this->loadViewsFrom(__DIR__ . "/../resources/views", 'LaravelProducts');
        $this->loadTranslationsFrom(__DIR__ . "/../resources/lang", 'LaravelProducts');
        //$this->loadJsonTranslationsFrom(__DIR__."/../resources/lang");
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'laravel-products'
        );

        $this->app->bind('LaravelProducts', LaravelProducts::class);
        $this->publishes([
            __DIR__ . '/../resources' => config('laravel-products.resource_path'),
        ], 'resources');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/Seeds' => database_path('migrations/seeds'),
        ], 'seeds');
    }
}
