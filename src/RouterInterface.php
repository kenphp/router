<?php

namespace Ken\Router;

/**
 * An interface of Router object
 */
interface RouterInterface
{
    /**
     * Adds route into collection
     * @param string    $route
     * @param callable  $handler Route handlers
     * @param string    $method  HTTP method
     * @param array     $options Route optional parameters
     * @return void
     */
    public function route($route, $handler, $method = 'GET', $options = []);

    /**
     * Adds GET route
     * @param string   $route
     * @param callable $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function get($route, $handler, $options = []);

    /**
     * Adds POST route
     * @param string    $route
     * @param callable  $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function post($route, $handler, $options = []);

    /**
     * Adds PUT route
     * @param string   $route
     * @param callable $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function put($route, $handler, $options = []);

    /**
     * Adds DELETE route
     * @param string    $route
     * @param callable  $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function delete($route, $handler, $options = []);
}
