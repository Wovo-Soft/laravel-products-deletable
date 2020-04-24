<?php

namespace Wovosoft\LaravelProducts\Tests;

use Wovosoft\LaravelProducts\Facades\LaravelProducts;
use Wovosoft\LaravelProducts\ServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelProductsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'laravel-products' => LaravelProducts::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
