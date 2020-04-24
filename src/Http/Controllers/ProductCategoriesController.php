<?php

namespace Wovosoft\LaravelProducts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Wovosoft\LaravelProducts\Traits\LaravelProductsTrait;

class ProductCategoriesController extends Controller
{
    use LaravelProductsTrait;

    /**
     * Used in the LaravelProductsTrait
     * @var string
     */
    private string $model;

    public function __construct()
    {
        $this->model = config('laravel-products.categories.model');
    }

    /**
     * Used in the 'search' method of LaravelProductsTrait
     * @var string[]
     */
    private array $search_fields = ['id', 'name', 'description'];
    /**
     * Used in the 'list' method of LaravelProductsTrait
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
        Route::post("LaravelProducts/categories/list", '\\' . __CLASS__ . '@list')->name('LaravelProducts.Categories.List');
        Route::post("LaravelProducts/categories/search", '\\' . __CLASS__ . '@search')->name('LaravelProducts.Categories.Search');
        Route::post("LaravelProducts/categories/store", '\\' . __CLASS__ . '@store')->name('LaravelProducts.Categories.Store');
        Route::post("LaravelProducts/categories/delete", '\\' . __CLASS__ . '@delete')->name('LaravelProducts.Categories.Delete');
    }
}
