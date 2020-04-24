<?php

use Illuminate\Support\Facades\Route;

//MAPPING_AREA_FOR_CRUD_DO_NOT_REMOVE_OR_EDIT_THIS_LINE_USE_AREA//


Route::name('Wovosoft.')
    ->prefix('backend')
    ->middleware(['web', 'auth'])
    ->group(function () {

        Wovosoft\LaravelProducts\Http\Controllers\ProductsController::routes();
        Wovosoft\LaravelProducts\Http\Controllers\ProductCategoriesController::routes();
        Wovosoft\LaravelProducts\Http\Controllers\ProductBrandsController::routes();
        //MAPPING_AREA_FOR_CRUD_DO_NOT_REMOVE_OR_EDIT_THIS_LINE//
    });
