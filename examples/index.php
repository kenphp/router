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

    // Route with optional parameter
    $router->get('[/{id}]', function($params = []) {
        if (isset($params['id'])) {
            echo 'Show user ID : ' . $params['id'];
        } else {
            echo 'Show a list of user';
        }
    });

    // Route with named parameter
    $router->get('/{name}', function($params = []) {
        echo 'Hello ' . $params['name'];
    });
});

// Get route path
$routePath = $_SERVER['PATH_INFO'] ?? '/';

// Get request method
$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

// Resolves route
$routeObject = $router->resolve($routePath, $requestMethod);

// If $routeObject is not null
if($routeObject) {
    // You can add some custom parameters here, like HttpRequest and HttpResponse object
    call_user_func_array($routeObject['handler'], [$routeObject['params']]);
} else {
    echo "Route '" . $routePath . "' not found.";
}
