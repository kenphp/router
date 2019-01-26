<?php

require __DIR__ . '/../vendor/autoload.php';

// create new instance
$router = new \Ken\Router\Router();

// define not found handler
$router->setNotFoundHandler(function($params = []) {
    echo "Route '" . $params['route'] . "' not found.";
});

// define routes
$router->get('/', function($params = []) {
    echo 'Index page';
});

// Route group
$router->group('/users', function() use ($router) {

    $router->get('/', function($params = []) {
        echo 'Show a list of user<br>';
    });

    // Route with named parameter
    $router->group('/{name}', function() use ($router) {
        $router->get('/', function($params = []) {
            echo 'Show user : ' . $params['name'] . '<br>';
        });

        $router->get('/trx[/{id}]', function($params = []) {
            if (isset($params['id'])) {
                echo 'Show transaction ID  ' . $params['id'] . ' of user '. $params['name'] .'<br>';
            } else {
                echo 'Show all transaction of user '. $params['name'] .'<br>';
            }
        });

    });
}, [
    'before' => [function() {
        echo 'This is executed before handler<br>';
    }],
    'after' => [function() {
        echo 'This is executed after handler<br>';
    }],
]);

// Get route path
$routePath = $_SERVER['PATH_INFO'] ?? '/';

// Get request method
$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

// Resolves route
$routeObject = $router->resolve($routePath, $requestMethod);

// If $routeObject is not null
if($routeObject) {
    if (isset($routeObject['before'])) {
        foreach ($routeObject['before'] as $before) {
            call_user_func($before);
        }
    }

    // You can add some custom parameters here, like HttpRequest and HttpResponse object
    call_user_func_array($routeObject['handler'], [$routeObject['params']]);

    if (isset($routeObject['after'])) {
        foreach ($routeObject['after'] as $after) {
            call_user_func($after);
        }
    }
} else {
    echo "Route '" . $routePath . "' not found.";
}
