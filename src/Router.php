<?php

namespace Ken\Router;

use Ken\Router\Exception\InvalidConfigurationException;

/**
 * Router Interface Implementation
 * @author Muhammad Safri Juliardi [ardi93@gmail.com]
 */
class Router implements RouterInterface
{

    /**
     * @var \Ken\Router\RouteParser
     */
    protected $routeParser;

    /**
     * @var \Ken\Router\RouteCollection
     */
    public $routeCollection;

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (isset($config['routeParser'])) {
            if ($config['routeParser'] instanceof RouteParser) {
                $this->routeParser = $config['routeParser'];
            } else {
                throw new InvalidConfigurationException("Expecting an instance of 'Ken\Router\RouteParser' for 'routeParser' configuration.");
            }
        } else {
            throw new InvalidConfigurationException("Configuration 'routeParser' is required.");
        }

        $this->routeCollection = new RouteCollection();
    }

    /**
     * Adds route into collection
     * @param string    $route
     * @param callable  $handler Route handlers
     * @param string    $method  HTTP method
     * @param array     $options Route optional parameters
     * @return void
     */
    public function route($route, $handler, $method = 'GET', $options = [])
    {
        $parsedRoute = $this->routeParser->parse($route);

        $options = array_merge($parsedRoute, $options);
        $options['method'] = $method;
        $options['handler'] = $handler;

        $routeObject = new Route($options);
        $this->routeCollection->add($routeObject->getName(), $routeObject);
    }

    /**
     * Adds GET route
     * @param string   $route
     * @param callable $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function get($route, $handler, $options = [])
    {
        $this->route($route, $handler, 'GET', $options);
    }

    /**
     * Adds POST route
     * @param string    $route
     * @param callable  $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function post($route, $handler, $options = [])
    {
        $this->route($route, $handler, 'POST', $options);
    }

    /**
     * Adds PUT route
     * @param string   $route
     * @param callable $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function put($route, $handler, $options = [])
    {
        $this->route($route, $handler, 'PUT', $options);
    }

    /**
     * Adds DELETE route
     * @param string    $route
     * @param callable  $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function delete($route, $handler, $options = [])
    {
        $this->route($route, $handler, 'DELETE', $options);
    }

    /**
     * Finds route object matched with the current request
     * @param  string $requestRoute
     * @param  string $method       HTTP Request Method
     * @return \Ken\Router\RouteInterface
     */
    public function handle($requestRoute, $method)
    {
        return $this->routeCollection->findMatchedRoute($requestRoute, $method);
    }
}
