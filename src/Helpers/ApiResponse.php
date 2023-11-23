<?php

declare(strict_types = 1);

namespace Wame\ApiResponse\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    private static ?string $code = null;

    /**
     * @var mixed|null
     */
    private static mixed $data = null;

    /**
     * @var mixed|null
     */
    private static mixed $additionalData = null;

    /**
     * @var mixed|null
     */
    private static mixed $errors = null;

    private static ?string $message = null;

    private static ?string $codePrefix = null;

    /**
     * Internal Response Code
     */
    public static function code(
        string $code,
        string $codePrefix = 'api',
    ): static {
        static::$code = $code;
        static::$codePrefix = $codePrefix;

        return new static();
    }

    /**
     * Response Data
     */
    public static function data(
        mixed $data,
    ): static {
        static::$data = $data;

        return new static();
    }

    /**
     * Response Data
     */
    public static function errors(
        mixed $errors,
    ): static {
        static::$errors = $errors;

        return new static();
    }

    /**
     * Response Data with Pagination
     *
     * @param null $resource
     */
    public static function collection(
        LengthAwarePaginator $pagination,
        $resource = null,
    ): static {
        if ($resource) {
            static::$data = (array) ($resource::collection($pagination))->toResponse(app(abstract: 'request'))->getData();
        } else {
            static::$data = $pagination->toArray();
        }

        return new static();
    }

    /**
     * Response Message
     */
    public static function message(
        string $message,
    ): static {
        static::$message = $message;

        return new static();
    }

    /**
     * Meta Data
     */
    public static function additionalData(
        mixed $additionalData,
    ): static {
        static::$additionalData = $additionalData;

        return new static();
    }

    /**
     * Response :D
     */
    public static function response(
        int $statusCode = 200,
    ): JsonResponse {
        if (0 === $statusCode) {
            $statusCode = 500;
        }
        if (self::$message) {
            $message = self::$message;
        } else {
            $message = self::$code ? __(key: self::$codePrefix . '.' . self::$code) : null;
        }

        if ('array' === gettype(value: self::$data)) {
            if (array_key_exists(key: 'data', array: self::$data)) {
                $response = collect(value: self::$data);
                $response = $response->merge(items: [
                    'code' => self::$code,
                    'errors' => self::$errors,
                    'additional_data' => self::$additionalData,
                    'message' => $message,
                ]);
            } else {
                $response = [
                    'data' => self::$data,
                    'code' => self::$code,
                    'errors' => self::$errors,
                    'additional_data' => self::$additionalData,
                    'message' => $message,
                ];
            }
        } else {
            $response = [
                'data' => self::$data,
                'code' => self::$code,
                'errors' => self::$errors,
                'additional_data' => self::$additionalData,
                'message' => $message,
            ];
        }

        return response()->json(data: $response)->setStatusCode(code: $statusCode);
    }

    /**
     * Handles exception
     */
    public static function exception(
        Exception $exception,
    ): JsonResponse {
        if (env('APP_DEBUG')) {
            dd($exception);
        }

        Log::build([
            'driver' => 'single',
            'path' => storage_path(path: 'logs/errors/' . date(format: 'Y-m-d') . '.log'),
        ])
            ->error(message: $exception);

        self::$message = __(key: 'wamesk-api-response.server-error');

        return self::response(statusCode: 500);
    }
}
