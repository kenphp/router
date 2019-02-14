## Router
A simple PHP Router for your web application.
This library is part of KenPHP Project, but can be used independently.

## Features
- Static Route Patterns
- Dynamic Route Patterns
- Named parameters
- Optional parameters
- Regex-based route patterns
- Subrouting
- Supports web application routing
- Before and After route middleware
- Custom handler when a route is not found

## What it does ?
- Store route patterns, handlers, and middlewares information
- Resolve request to matching patterns

## What it doesn't ?
- Parse route path from $_SERVER or any other means. You must provide the route path and method to `Router::resolve` method.
- Execute middlewares and handlers. It only returns an array containing matched route handlers, middlewares, and parameters found in the request.
- **Why isn't this library receives `Psr\Http\Message\RequestInterface` implementation and returns `Psr\Http\Message\ResponseInterface` ?** <br>
    This library aims to gives as much freedom as possible to the user. Not everyone are using PSR-7 implementation and we want to respect that.

## Requirements
- PHP 7.0 or greater

## Installation
The easiest way to install is using Composer
```
$ composer require kenphp/router
```

## Methods
1. `route($method, $route, $handler, $options = []) : void`

    Example :
    ```php
    $router->route('GET', '/users', ['UserController', 'listUsers']);
    ```
2. `get($route, $handler, $options = []) : void`

    Example :
    ```php
    $router->get('/users/{id}', ['UserController', 'getUser']);
    ```
3. `head($route, $handler, $options = []) : void`

    Example :
    ```php
    $router->head('/users', ['UserController', 'listUsers']);
    ```
4. `post($route, $handler, $options = []) : void`

    Example :
    ```php
    $router->post('/users', ['UserController', 'createUser']);
    ```
5. `put($route, $handler, $options = []) : void`

    Example :
    ```php
    $router->put('/users/{id}', ['UserController', 'updateUser']);
    ```
6. `delete($route, $handler, $options = []) : void`

    Example :
    ```php
    $router->delete('/users/{id}', ['UserController', 'deleteUser']);
    ```
7. `group($route, $fn, $options = []) : void`

    Example :
    ```php
    $router->group('/api', function() use ($router) {
        $router->get('/products/{id}', ['ProductController', 'getProduct']);
    });
    ```
8. `setNotFoundHandler(callable $handler) : void`

    Example :
    ```php
    $router->setNotFoundHandler(function() {
        echo 'Page not found.';
    });
    ```
9. `resolve($requestRoute, $method) : null|array`

    This function would return an array containing the following keys :
    - `handler`
    - `params`
    - Optional keys. This would be filled with any data from the `$options` parameter.

    Example :
    ```php
    $router->get('/users/{id}', ['UserController', 'getUser'], [
        'namespace' => 'app\controllers'
    ]);

    $routeArray = $router->resolve('/users/1', 'GET');

    /**
     * $routeArray would contains
     * [
     *    'handler' => ['UserController', 'getUser'],
     *    'params' => ['id' => 1],
     *    'namespace' => 'app/controllers',
     * ]
     */
```


## Examples
- [Usage example for web application](examples/index.php)
