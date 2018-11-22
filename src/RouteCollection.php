<?php

namespace Ken\Router;

use Ken\Router\Exception\InvalidConfigurationException;

/**
 * A class that manages a collection of \Ken|Router\Route objects
 */
class RouteCollection implements RouteCollectionInterface
{
    /**
     * A collection of route object
     * @var \Ken\Router\Route[]
     */
    protected $_collections = [];

    /**
     * @inheritDoc
     */
    public function add($name, Route $route)
    {
        $name = trim($name, '/');
        if ($name == '') {
            $name = 'root';
        }

        if ($this->has($name)) {
            throw new InvalidConfigurationException('Route name already exist.');
        }

        $this->_collections[$name] = $route;
    }

    /**
     * @inheritDoc
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->_collections[$name]);
        }
    }

    /**
     * @inheritDoc
     */
    public function replace($name, Route $route)
    {
        if ($this->has($name)) {
            $this->_collections[$name] = $route;
        } else {
            $this->add($name, $route);
        }
    }

    /**
     * @inheritDoc
     */
    public function find($name)
    {
        if ($this->has($name)) {
            return $this->_collections($name);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function findMatchedRoute($requestRoute, $method)
    {
        foreach ($this->_collections as $name => $route) {
            if ($route->isMatch($requestRoute, $method)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function has($name)
    {
        return isset($this->_collections[$name]);
    }
}
