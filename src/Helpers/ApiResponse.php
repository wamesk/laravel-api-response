<?php

namespace Wame\ApiResponse\Helpers;

class ApiResponse
{
    /**
     * @var string|null
     */
    private static string|null $code = null;

    /**
     * @var mixed|null
     */
    private static mixed $data = null;

    /**
     * @var string|null
     */
    private static string|null $message = null;

    /**
     * Internal Response Code
     *
     * @param string $code
     * @return static
     */
    public static function code(string $code): static
    {
        static::$code = $code;

        return new static;
    }

    /**
     * Response Data
     *
     * @param mixed $data
     * @return static
     */
    public static function data(mixed $data): static
    {
        static::$data = $data;

        return new static;
    }

    /**
     * Response Data with Pagination
     *
     * @param mixed $data
     * @param $resource
     * @return static
     */
    public static function collection(mixed $pagination, $resource = null): static
    {
        if ($resource) static::$data = (array)($resource::collection($pagination))->toResponse(app('request'))->getData();
        else static::$data = $pagination->toArray();

        return new static;
    }

    /**
     * Response Message
     *
     * @param string $message
     * @return static
     */
    public static function message(string $message): static
    {
        static::$message = $message;

        return new static;
    }

    /**
     * Response :D
     *
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function response(int $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        if (gettype(self::$data) === 'array') {
            if (key_exists('data', self::$data)) {
                $response = collect(self::$data);
                $response = $response->merge([
                    'code' => self::$code,
                    'message' => self::$message ?? __('api.' . self::$code),
                ]);
            } else {
                $response = [
                    'data' => self::$data,
                    'code' => self::$code,
                    'message' => self::$message ?? __('api.' . self::$code),
                ];
            }
        } else {
            $response = [
                'data' => self::$data,
                'code' => self::$code,
                'message' => self::$message ?? __('api.' . self::$code),
            ];
        }

        return response()->json($response)->setStatusCode($statusCode);
    }
}
