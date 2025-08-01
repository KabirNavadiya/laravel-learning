<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    /**
     * Return a success response with data.
     *
     * @param  mixed  $data  The data to return in the response
     * @param  string  $message  The success message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function success(
        mixed $data = null,
        string $message = 'api.success',
        int $status = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => __($message),
            'data' => $data,
        ], $status, $headers);
    }

    /**
     * Return a paginated response with data and pagination metadata.
     *
     * @param  LengthAwarePaginator<int, mixed>  $paginator  The paginator instance
     * @param  string  $message  The success message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function paginated(
        LengthAwarePaginator $paginator,
        string $message = 'api.success',
        int $status = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => __($message),
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ], $status, $headers);
    }

    /**
     * Return a paginated response with resource data and pagination metadata.
     *
     * @param  AnonymousResourceCollection  $resource  The resource collection instance
     * @param  string  $message  The success message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function paginatedResource(
        AnonymousResourceCollection $resource,
        string $message = 'api.success',
        int $status = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => __($message),
            'data' => $resource->collection,
            'meta' => [
                'current_page' => $resource->resource->currentPage(),
                'last_page' => $resource->resource->lastPage(),
                'per_page' => $resource->resource->perPage(),
                'total' => $resource->resource->total(),
            ],
        ], $status, $headers);
    }

    /**
     * Return an error response.
     *
     * @param  string  $message  The error message or translation key
     * @param  array<string, mixed>  $errors  Additional error details
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function error(
        string $message = 'api.error',
        array $errors = [],
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => __($message),
            'errors_meta' => $errors,
        ], $status, $headers);
    }

    /**
     * Return an exception response.
     *
     * @param  \Throwable  $e  The exception that occurred
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function exception(\Throwable $e, int $status = Response::HTTP_INTERNAL_SERVER_ERROR, array $headers = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'exception' => get_class($e),
        ], $status, $headers);
    }

    /**
     * Return a response from a resource.
     *
     * @param  JsonResource  $resource  The resource to convert to a response
     * @param  string  $message  The success message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function fromResource(
        JsonResource $resource,
        string $message = 'api.success',
        int $status = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => __($message),
            'data' => $resource->resolve(app('request')),
        ], $status, $headers);
    }

    /**
     * Return a validation error response.
     *
     * @param  array<string, mixed>  $errors  Validation errors
     * @param  string  $message  The error message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function validationError(
        array $errors,
        string $message = 'api.validation_failed',
        int $status = Response::HTTP_UNPROCESSABLE_ENTITY,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => __($message),
            'errors_meta' => $errors,
        ], $status, $headers);
    }

    /**
     * Return an unauthorized error response.
     *
     * @param  string  $message  The error message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function unauthorized(
        string $message = 'api.unauthenticated',
        int $status = Response::HTTP_UNAUTHORIZED,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => __($message),
        ], $status, $headers);
    }

    /**
     * Return a forbidden error response.
     *
     * @param  string  $message  The error message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function forbidden(
        string $message = 'api.forbidden',
        int $status = Response::HTTP_FORBIDDEN,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => __($message),
        ], $status, $headers);
    }

    /**
     * Return a not found error response.
     *
     * @param  string  $message  The error message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function notFound(
        string $message = 'api.not_found',
        int $status = Response::HTTP_NOT_FOUND,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => __($message),
        ], $status, $headers);
    }

    /**
     * Return a no content response.
     *
     * @param  string  $message  The success message or translation key
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function noContent(
        string $message = 'api.no_content',
        int $status = Response::HTTP_NO_CONTENT,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => __($message),
        ], $status, $headers);
    }

    /**
     * Return a custom response.
     *
     * @param  array<string, mixed>  $data  The data to include in the response
     * @param  int  $status  The HTTP status code
     * @param  array<string, string>  $headers  Additional headers to include
     */
    public static function custom(array $data, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return response()->json($data, $status, $headers);
    }
}
