<?php

namespace Ken\Router;

/**
 * An interface of Route object
 */
interface RouteInterface
{
    /**
     * Retrieves route HTTP method
     *
     * @return string
     */
    public function getMethod();

    /**
     * Sets accepted HTTP method
     *
     * @param string $method
     * @throws \Ken\Router\Exception\InvalidConfigurationException
     */
    public function setMethod($method);

    /**
     * Retrieves route name
     * @return string
     */
    public function getName();

    /**
     * Sets route name
     * @param string $name
     */
    public function setName($name);

    /**
     * Sets route regex pattern
     * @param string $regexPattern
     */
    public function setRegexPattern($regexPattern);

    /**
     * Retrieves route path
     *
     * @return string
     */
    public function getPath();

    /**
     * Sets route path
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * Sets route handler
     *
     * @param mixed $handler
     * @return void
     */
    public function setHandler($handler);

    /**
     * Checks whether this route matches with the requested URL
     * @param string $requestString The route request string
     * @param string $method The HTTP Request method
     * @return bool
     */
    public function isMatch($requestString, $method);

    /**
     * Dispatches route's handler
     *
     * @return array An array that is consisted of
     * [
     *      'path' => 'matched route path',
     *      'method' => 'http method',
     *      'handler' => callable,
     * ]
     */
    public function dispatch();
}
