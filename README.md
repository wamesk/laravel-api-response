# Laravel Api Response

## Laravel package for easy formatted api response

**Installation**

```bash
composer require wamesk/laravel-api-response
```

**Usage**

For basic response use class and call response() function and pass status code needed *(default 200)*.

```php
ApiResponse::response(201);
```

You can also pass message in your response by adding `message()` function before response function.

```php
ApiResponse::message('Hello')->response(201);
```

You can pass internal code using `message()` function that helps you find of response in case of error.

```php
ApiResponse::code('1.2.1)->message('Hello')->response(201);
```

You can pass data using `data()` function.

```php
ApiResponse::data(['id' => 1, 'name' => 'Jhon Jhonson'])->code('1.2.1)->message('Hello')->response(201);
```

In case you need pagination in your api you can use `collection()` function instead of `data()` function.
You can use this function by passing paginated data, and you can also pass Resource for better data formatting *(Resource is not required)*

```php
$users = User::paginate(10);
ApiResponse::collection($users, UserResource::class)->code('1.2.1)->message('Hello')->response(201);
```