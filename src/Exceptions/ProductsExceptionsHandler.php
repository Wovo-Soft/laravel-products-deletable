<?php

namespace Wovosoft\LaravelProducts\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Http\Response;

/**
 * Custom Exception Handler. It is not a good idea to override the root handler.
 * So, this exception handler is made to generate HttpResponse
 * Class ProductsExceptionsHandler
 * @package Wovosoft\LaravelProducts\Exceptions
 */
class ProductsExceptionsHandler
{
    private Throwable $e;
    private Request $request;

    public function __construct(Request $request, Throwable $e)
    {
        $this->request = $request;
        $this->e = $e;
    }

    public function render()
    {
        if ($this->request->ajax()) {
            $output = [
                "status" => false,
                "type" => "danger",
                "title" => "Failed"
            ];
            if (env('APP_DEBUG')) {
                $output["msg"] = $this->e->getMessage();    //should be removed when front-end is updated with new 'message' key. Because Laravel returns as message key.
                $output["message"] = $this->e->getMessage();
                $output['file'] = $this->e->getFile();
                $output["line"] = $this->e->getLine();
                $output["previous"] = $this->e->getPrevious();
                $output["trace"] = $this->e->getTrace();
            } else {
                $output["msg"] = "There is an error";
                $output["message"] = "There is an error";
            }
            $status = Response::HTTP_NOT_FOUND;
            if ($this->e instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
            } elseif ($this->e instanceof InvalidArgumentException) {
                $status = Response::HTTP_FORBIDDEN;
            } elseif ($this->e instanceof NotFoundHttpException) {
                $status = $this->e->getCode();
            }

            return response()
                ->json($output, $status);
        }
        throw $this->e;
    }
}
