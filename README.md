# Laravel Api Response

## Laravel package for easy formatted api response

**Installation**

```bash
composer require wamesk/laravel-api-response
```

**Usage**

For basic response use class and call response() function and pass status code needed *(default 200)*.

This will not send any data itself, this function is used last to generate response and set status code.

```php
return ApiResponse::response(201);
```

Response:
```json
```

You can also pass message in your response by adding `message()` function before response function.

```php
return ApiResponse::message('Hello')->response(201);
```

Response:

```json
{
  "message": "Hello"
}
```

You can pass internal code using `message()` function that helps you find of response in case of error.

```php
return ApiResponse::code('1.2.1')->message('Hello')->response(201);
```

Response:

```json
{
  "message": "Hello",
  "code": "1.2.1"
}
```

You can pass data using `data()` function.

```php
return ApiResponse::data(['id' => 1, 'name' => 'Jhon Jhonson'])->code('1.2.1')->message('Hello')->response(201);
```

Response:

```json
{
  "message": "Hello",
  "code": "1.2.1",
  "data": {
    "id": 1,
    "name": "Jhon Jhonson"
  }
}
```

In case you need pagination in your api you can use `collection()` function instead of `data()` function.
You can use this function by passing paginated data, and you can also pass Resource for better data formatting *(Resource is not required)*

```php
$users = User::paginate(10);

return ApiResponse::collection($users, UserResource::class)->code('1.2.1')->message('Hello')->response(201);
```

Response:

```json
{
    "data": [
        {
            "id": 1,
            "name": "Jhon Jhonson",
        },
        {
            "id": 2,
            "name": "Patrick Jhonson",
        }
    ],
    "links": {
        "first": "http://localhost:8888/api/v1/test?page=1",
        "last": "http://localhost:8888/api/v1/test?page=2",
        "prev": null,
        "next": "http://localhost:8888/api/v1/test?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 3,
        "links": [
            {
                "url": null,
                "label": "pagination.previous",
                "active": false
            },
            {
                "url": "http://localhost:8888/api/v1/test?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "http://localhost:8888/api/v1/test?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "http://localhost:8888/api/v1/test?page=3",
                "label": "2",
                "active": false
            },
            {
                "url": "http://localhost:8888/api/v1/test?page=2",
                "label": "pagination.next",
                "active": false
            }
        ],
        "path": "http://localhost:8888/api/v1/test",
        "per_page": 2,
        "to": 2,
        "total": 6
    },
    "code": "1.2.1",
    "message": "Hello"
}
```