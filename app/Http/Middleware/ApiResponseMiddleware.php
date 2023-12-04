<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if response is a redirect response
        if ($response instanceof RedirectResponse) {
            return $response;
        }

        // Check if response expects json response
        if (!($response instanceof JsonResponse)) {
            return $response;
        }

        $data = $response->getData();

        // Check if response is
        if ($response instanceof ModelNotFoundException) {
            $model = $response->getModel();
            $modelName = str_replace(['_', '-'], ' ', Str::studly(class_basename($model)));

            $data->message = $modelName . ' Record not found';
        }

        if (!is_object($data)) {
            $data = new stdClass();
        }

        if (!property_exists($data, 'http_status')) {
            $data->http_status = $response->status();
        }

        $success = $response->status() < 400;

        if (!property_exists($data, 'success')) {
            $data->success = $success;
        }

        // Add error code when the request has error
        if (!property_exists($data, 'error_code') && !$success) {
            $data->error_code = $this->getStatusKey($response->status());
        }

        // If request has validation error
        // Set the data message as the first validation error message
        if ($response->status() === 422) {
            if (property_exists($data, 'errors')) {
                $message = $this->getFirstErrorMessage($data->errors);
                if ($message) {
                    $data->message = $message;
                }
            }
        }

        if ($response->status() === 404 && str_starts_with($data->message, 'No query results for model') ) {
            $text = [];
            preg_match('#\[(.*?)\]#', $data->message, $text);
            if (count($text) > 1) {
                $model = explode("\\", $text[1]);
                $modelWords = preg_split('/(?=[A-Z])/', $model[count($model) - 1] );
                $modelProperDescription = implode(' ', $modelWords);
                $data->message = $modelProperDescription . ' Record not found';
            }
        }

        $response->setData($data);

        return $response;
    }


    /**
     * Get key that represents an HTTP status code
     *
     * @param Int $code - http status code
     * @return String $key
     */
    private function getStatusKey($code): String
    {
        $reflectionClass = new \ReflectionClass(Response::class);
        return array_search($code, $reflectionClass->getConstants(), true);
    }

    /**
     * Get the first error message
     * @return String or null
     */
    private function getFirstErrorMessage($errors): ?String
    {
        $errorsArray = (array) $errors;
        if (count($errorsArray) > 0) {
            $firstError = current($errorsArray);
            if (count($firstError) > 0 && $firstError[0]) {
                return $firstError[0];
            }
        }
    }
}
