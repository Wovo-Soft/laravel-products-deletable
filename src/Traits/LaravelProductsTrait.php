<?php

namespace Wovosoft\LaravelProducts\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Wovosoft\LaravelProducts\Builders\QueryBuilder;
use Wovosoft\LaravelProducts\Exceptions\ProductsExceptionsHandler;
use Wovosoft\LaravelProducts\Exceptions\ProductsStoreException;

trait LaravelProductsTrait
{
    /**
     * Storing Model Method
     * @param Request $request
     * @return JsonResponse|void
     * @throws \Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            if (!method_exists($this, 'storeSearchBy')) {
                throw new ProductsStoreException('storeSearchBy Method is not Exists', Response::HTTP_METHOD_NOT_ALLOWED);
            }
            if (!method_exists($this, 'storeData')) {
                throw new ProductsStoreException('storeData Method is not Exists', Response::HTTP_METHOD_NOT_ALLOWED);
            }

            $item = $this->model::updateOrCreate($this->storeSearchBy($request), $this->storeData($request));

            if (!$item) {
                throw new ProductsStoreException("Unable to Save the Data", 304);
            }

            return response()->json([
                "status" => true,
                "title" => 'SUCCESS!',
                "type" => "success",
                "msg" => ' Successfully Done'
            ]);
        } catch (\Throwable $exception) {
            return (new ProductsExceptionsHandler($request, $exception))->render();
        }
    }

    /**
     * Returns Paginated Response of Items. Simple for Datatable
     * @param Request $request [
     *                              string filter Search Query,
     *                              int per_page = 10 Numbers of items Per Page,
     *                              string orderBy = 'id',
     *                              string order ='desc',
     *                              array columns =['*'] fields to be selected.
     *                          ]
     * @return JsonResponse|void
     * @throws \Throwable
     */
    public function list(Request $request)
    {
        try {
            $items = (new QueryBuilder($this->model::query()))->select(['*']);
            if (isset($this->listWith) && is_array($this->listWith) && count($this->listWith)) {
                $items->with($this->listWith);
            }
            return response()->json(
                $items->defaultDatatable($request),
                200, [],
                JSON_PRETTY_PRINT
            );
        } catch (\Throwable $exception) {
            return (new ProductsExceptionsHandler($request, $exception))->render();
        }
    }

    /**
     * @param Request $request [array search_selects=null, int limit=30, string order='id', string orderBy='asc',string query=null]
     * @return JsonResponse
     * @throws \Throwable
     */
    public function search(Request $request): JsonResponse
    {
        try {
            if ($request->post('search_selects') && is_array($request->post('search_selects')) && count($request->post('search_selects'))) {
                $selects = $request->post('search_selects');
            } elseif (isset($this->search_selects)) {
                $selects = $this->search_selects;
            } else {
                $selects = ['*'];
            }

            $items = $this->model::query()
                ->select($selects)
                ->limit($request->post('limit') ?? 30)
                ->orderBy($request->post('order') ?? 'id', $request->post('orderBy') ?? 'asc');

            $c = 0;
            foreach ($this->search_fields as $search_field) {
                if ($c === 0) {
                    if ($search_field === 'id') {
                        $items->where($search_field, '=', $request->post('query'));
                    } else {
                        $items->where($search_field, 'LIKE', '%' . $request->post('query') . '%');
                    }
                } else {
                    if ($search_field === 'id') {
                        $items->orWhere($search_field, '=', '%' . $request->post('query') . '%');
                    } else {
                        $items->orWhere($search_field, 'LIKE', '%' . $request->post('query') . '%');
                    }
                }
                $c++;
            }
            return response()->json($items->get());
        } catch (\Throwable $exception) {
            return (new ProductsExceptionsHandler($request, $exception))->render();
        }
    }

    /**
     * @param Request $request [int id]
     * @return JsonResponse|ProductsExceptionsHandler
     * @throws \Exception
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $item = $this->model::find($request->post('id'));
            if (!$item) {
                throw new ModelNotFoundException('Item Not Found', 404);
            }
            if (!$item->delete()) {
                throw new \Exception('Unable to Delete', 405);
            }
            return response()->json([
                "status" => true,
                "title" => "Success",
                "type" => "success",
                "msg" => "Successfully Delete"
            ]);
        } catch (\Throwable $exception) {
            return new ProductsExceptionsHandler($request, $exception);
        }
    }
}
