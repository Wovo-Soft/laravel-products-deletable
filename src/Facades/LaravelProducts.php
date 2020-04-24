<?php

namespace Wovosoft\LaravelProducts\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelProducts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LaravelProducts';
    }
}
