<?php


namespace Wovosoft\LaravelProducts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Wovosoft\LaravelProducts\Exceptions\ProductsExceptionsHandler;
use Wovosoft\LaravelProducts\Exceptions\ProductsStoreException;
use Wovosoft\LaravelProducts\Traits\LaravelProductsTrait;


class ProductsController extends Controller
{
    use LaravelProductsTrait;

    /**
     * Used in the LaravelProductsTrait
     * @var string
     */
    private string $model;

    public function __construct()
    {
        $this->model = config('laravel-products.products.model');
    }

    /**
     * Used in the 'list' method of LaravelProductsTrait
     * @var string[]
     */
    private array $listWith = ['categories', 'brands', 'productAttributes', 'variations'];

    /**
     * List of Required Routes. These Routes are isolated so that these can be initiated from anywhere.
     * By default, these routes are initiated in the package's routes.php file. To disable initiating
     * by the package itself, disable routes_enable variable in config file.
     */
    public static function routes()
    {
        Route::post("LaravelProducts/list", '\\' . __CLASS__ . '@list')->name('LaravelProducts.List');
        Route::post("LaravelProducts/search", '\\' . __CLASS__ . '@search')->name('LaravelProducts.Search');
        Route::post("LaravelProducts/store", '\\' . __CLASS__ . '@store')->name('LaravelProducts.Store');
        Route::post("LaravelProducts/delete", '\\' . __CLASS__ . '@delete')->name('LaravelProducts.Delete');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */

    public function store(Request $request): JsonResponse
    {
        try {
            $item = $this->model::findOrNew($request->post('id'));
            if (!$item) {
                throw new ModelNotFoundException('Item Not Found', 404);
            }
            $item->name = $request->post('name');
            $item->upc = $request->post('upc');
            $item->type = $request->post('type') ?? 'countable';
            $item->unit = $request->post('unit') ?? 'Quantity';
            $item->url = $request->post('url') ?? '';
            $item->cost = $request->post('cost') ?? 0;
            $item->price = $request->post('price') ?? 0;
            $item->description = $request->post('description') ?? '';

            // this can be done in the Model as setImageAttribute. It should be done in Model, when front-end issue is fixed.
            // The issue appears when editing, the image returned to front-end as string, but b-form-file requires it to be File.
            // A custom component should be made to fix the issue.
            if ($request->hasFile('image_upload')) {
                $item->image = $request->file('image_upload')->store('products', 'public');
            } else {
                $item->image = $request->post('image');
            }

            if (!$item->save()) {
                //304 Not Modified
                throw new ProductsStoreException("Unable to Save the Model", 304);
            }

            $item->setCategories(collect($request->post('categories'))->map(function ($cat) use ($item) {
                return [
                    'category_id' => $cat['id'],
                    'product_id' => $item->id
                ];
            })->toArray());

            $item->setBrands(collect($request->post('brands'))->map(function ($cat) use ($item) {
                return [
                    'brand_id' => $cat['id'],
                    'product_id' => $item->id
                ];
            })->toArray());
            $item->setProductAttributes($request->post('product_attributes'));
            $item->setVariations($request->post('variations'));
            return response()->json([
                "status" => true,
                "title" => 'SUCCESS!',
                "type" => "success",
                "msg" => ($request->post('id') ? 'Edited' : 'Added') . ' Successfully'
            ]);
        } catch (\Throwable $exception) {
            return (new ProductsExceptionsHandler($request, $exception))->render();
        }
    }
}
