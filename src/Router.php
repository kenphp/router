<?php

namespace Ken\Router;

/**
 * @author Muhammad Safri Juliardi [ardi93@gmail.com]
 */
class Router {

    /**
     * @var string
     */
    const PARAMS_PATTERN = '~(?<optional>\\[|\\[/){0,1}(?:\\{){1}(?<param>[a-zA-Z0-9]+)(?:\\}){1}(?:\\]){0,1}~is';

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var array
     */
    protected $baseOptions;

    /**
     * @var array
     */
    protected $routeList;

    /**
     * @var callable
     */
    protected $notFoundHandler;

    /**
     * @param array $config
     */
    public function __construct($config = []) {
        $this->routeList = [];
        $this->basePath = '';
        $this->baseOptions = [];
        $this->notFoundHandler = null;

        if (isset($config['notFoundHandler'])) {
            if (is_callable($config['notFoundHandler'])) {
                $this->setNotFoundHandler($config['notFoundHandler']);
            }
        }
    }
    /**
     * Adds route into collection
     * @param string    $method  HTTP method
     * @param string    $route
     * @param callable  $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function route($method, $route, $handler, $options = [])
    {
        $routeObject = array_merge([
            'pattern' => $this->parseRoute($route),
            'handler' => $handler,
        ], $options, $this->baseOptions);

        $arrMethod = explode("|", $method);

        foreach ($arrMethod as $value) {
            $value = strtolower($value);
            $this->routeList[$value][] = $routeObject;
        }
    }

    protected function parseRoute($route) {
        $r = preg_replace_callback(self::PARAMS_PATTERN, function($matches) {
            extract($matches);
            if (!empty($optional)) {
                return "((/)*(?<{$param}>(\w)+)){0,1}";
            } else {
                return "(?<$param>(\w)+)";
            }
        }, $route);

        return "~^$r$~is";
    }

    protected function trimRoute($route) {
        // ensure that the route doesn't have double backslash and trailing backslash
        $route = $this->basePath . $route;
        $route = rtrim($route, '/');
        $route = '/' . ltrim($route, '/');
        $route = str_replace('//', '/', $route);

        return $route;
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
        $route = $this->trimRoute($route);
        $this->route('GET', $route, $handler, $options);
    }

    /**
     * Adds HEAD route
     * @param string   $route
     * @param callable $handler Route handlers
     * @param array     $options Route optional parameters
     * @return void
     */
    public function head($route, $handler, $options = [])
    {
        $route = $this->trimRoute($route);
        $this->route('HEAD', $route, $handler, $options);
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
        $route = $this->trimRoute($route);
        $this->route('POST', $route, $handler, $options);
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
        $route = $this->trimRoute($route);
        $this->route('PUT', $route, $handler, $options);
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
        $route = $this->trimRoute($route);
        $this->route('DELETE', $route, $handler, $options);
    }

    /**
     * Adds console route. It can be used for console application.
     * @param  string $route
     * @param  callable $handler
     * @param  array  $options Route optional parameters
     * @return void
     */
    public function console($route, $handler, $options = []) {
        $this->route('CONSOLE', $route, $handler, $options);
    }

    /**
     * Adds grouped route
     * @param  string $route
     * @param  callable $fn
     * @param  array  $options
     * @return void
     */
    public function group($route, $fn, $options = []) {
        $basePath = $this->basePath;
        $baseOptions = $this->baseOptions;
        $this->basePath = $this->basePath . $route;
        $this->baseOptions = array_merge($options, $this->baseOptions);

        call_user_func($fn);

        $this->basePath = $basePath;
        $this->baseOptions = $baseOptions;
    }

    /**
     * Sets a handler when a route is not found
     * @param callable $handler
     */
    public function setNotFoundHandler($handler) {
        $this->notFoundHandler = $handler;
    }

    /**
     * Finds route object matched with the current request.
     * If route not found, it will checks whether **$notFoundHandler**
     * has a value. If it does, this function will returns an array containing
     * **$notFoundHandler** in `handler` key.
     * If **$notFoundHandler** is null, this function will returns `null`
     * @param  string $requestRoute
     * @param  string $method       HTTP Request Method
     * @return null|array
     */
    public function resolve($requestRoute, $method)
    {
        $method = strtolower($method);

        foreach ($this->routeList[$method] as $routeObject) {
            $m = preg_match($routeObject['pattern'], $requestRoute, $matches);

            if ($m === 1) {
                $keys = array_keys($matches);
                $params = [];
                foreach ($keys as $key) {
                    if (is_string($key)) {
                        $params[$key] = $matches[$key];
                    }
                }

                $result['handler'] = $routeObject['handler'];
                $result['params'] = $params;

                $result['before'] = isset($routeObject['before']) ? $routeObject['before'] : [];
                $result['after'] = isset($routeObject['after']) ? $routeObject['after'] : [];

                return $result;
            }
        }

        if ($this->notFoundHandler) {
            return [
                'handler' => $this->notFoundHandler,
                'params' => [
                    'route' => $requestRoute,
                ],
                'before' => [],
                'after' => [],
            ];
        } else {
            return null;
        }
    }


}
