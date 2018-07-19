<?php

namespace Ken\Router;

/**
 * An interfce of RouteCollection
 */
interface RouteCollectionInterface
{

    /**
     * Adds route object into collection
     * @param string $name  The name of the route
     * @param Route  $route \Ken\Router\Route instance
     * @throws \InvalidArgumentException when the route name already exist in the collection
     */
    public function add($name, Route $route);

    /**
     * Removes route object from collection
     * @param string $name  The name of the route
     */
    public function remove($name);

    /**
     * Replaces route object from collection
     * @param string $name  The name of the route
     * @param Route  $route \Ken\Router\Route instance
     */
    public function replace($name, Route $route);

    /**
     * Searches route object in the collection by its name
     * @param string $name  The name of the route
     * @return Route \Ken\Router\Route instance or null if not found
     */
    public function find($name);

    /**
    * Searches a route object that matched the requested route
    * @param string $requestRoute The route request string
    * @param string $method The HTTP Request method
    * @return Route \Ken\Router\Route instance or null if not found
     */
    public function findMatchedRoute($requestRoute, $method);

    /**
     * Checks wether a route name already exist in the collection
     * @param string $name  The name of the route
     * @return bool
     */
    public function has($name);
}
