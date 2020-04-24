<?php

namespace Wovosoft\LaravelProducts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Wovosoft\LaravelProducts\Traits\LaravelProductsTrait;

class ProductBrandsController extends Controller
{
    use LaravelProductsTrait;

    /**
     * Used in the LaravelProductsTrait
     * @var string
     */
    private string $model;

    public function __construct()
    {
        $this->model = config('laravel-products.brands.model');
    }

    /**
     * Used in the 'search' method of LaravelProductsTrait
     * @var string[]
     */
    private array $search_fields = ['id', 'name', 'description'];
    /**
     * Columns to be selected when search result returns.
     * Used in the 'search' method of LaravelProductsTrait
     * @var string[]
     */
    private array $search_selects = ['id', 'name'];

    /**
     * Callback Function used in 'store' method of LaravelProductsTrait
     * @param Request $request
     * @return array
     */
    private function storeSearchBy(Request $request): array
    {
        return [
            "id" => $request->post('id')
        ];
    }

    /**
     * Callback Function used in 'store' method of LaravelProductsTrait
     * @param Request $request
     * @return array
     */
    private function storeData(Request $request): array
    {
        return [
            "name" => $request->post('name'),
            "description" => $request->post('description')
        ];
    }

    /**
     * List of Required Routes. These Routes are isolated so that these can be initiated from anywhere.
     * By default, these routes are initiated in the package's routes.php file. To disable initiating
     * by the package itself, disable routes_enable variable in config file.
     */
    public static function routes(): void
    {
        Route::post("LaravelProducts/brands/list", '\\' . __CLASS__ . '@list')->name('LaravelProducts.Brands.List');
        Route::post("LaravelProducts/brands/search", '\\' . __CLASS__ . '@search')->name('LaravelProducts.Brands.Search');
        Route::post("LaravelProducts/brands/store", '\\' . __CLASS__ . '@store')->name('LaravelProducts.Brands.Store');
        Route::post("LaravelProducts/brands/delete", '\\' . __CLASS__ . '@delete')->name('LaravelProducts.Brands.Delete');
    }

}
