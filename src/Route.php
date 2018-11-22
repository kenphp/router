<?php

namespace Ken\Router;

use Ken\Router\Exception\InvalidConfigurationException;

/**
 * Route class
 * @author Muhammad Safri Juliardi <ardi93@gmail.com>
 */
class Route implements RouteInterface
{
    const REGEX_DELIMITER = '~';

    /**
     * Allowed HTTP methods
     *
     * @var array
     */
    protected $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * The accepted HTTP request method
     * @var string
     */
    protected $method = 'GET';

    /**
     * Route name
     * @var string
     */
    protected $name;

    /**
     * Route URL
     * @var string
     */
    protected $path;

    /**
     * Regex Route URL
     * @var string
     */
    protected $regexPattern;

    /**
     * Route parameters definition
     * @var array
     */
    protected $params;

    /**
     * Request parameters
     * @var array
     */
    protected $requestParams;

    /**
     * Route handler
     * @var mixed
     */
    protected $handler;

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $requiredConfig = [
            'path' => 'setPath',
            'regexPattern' => 'setRegexPattern',
            'handler' => 'setHandler',
            'params' => 'setParams',
        ];

        foreach ($requiredConfig as $key => $methodName) {
            if (isset($config[$key])) {
                call_user_func([$this, $methodName], $config[$key]);
            } else {
                throw new InvalidConfigurationException("Configuration '{$key}' is required.");
            }
        }

        if (isset($config['method'])) {
            $this->setMethod($config['method']);
        }
        if (isset($config['name'])) {
            $this->setName($config['name']);
        } else {
            $this->setName($config['path']);
        }
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function setMethod($method)
    {
        if (in_array($method, $this->allowedMethods)) {
            $this->method = $method;
        } else {
            throw new InvalidConfigurationException("Method '{$method}' is not allowed");
        }
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;

        if (empty($name)) {
            $this->name = $this->path;
        }
    }

    /**
     * @inheritDoc
     */
    public function setRegexPattern($regexPattern)
    {
        if (substr($regexPattern, 0, 1) != self::REGEX_DELIMITER) {
            $regexPattern = self::REGEX_DELIMITER . $regexPattern;
        }
        $regexSuffix = self::REGEX_DELIMITER . 'is';
        if (substr($regexPattern, strlen($regexPattern) - 3, 3) != $regexSuffix) {
            $regexPattern .= $regexSuffix;
        }
        $this->regexPattern = $regexPattern;
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @inheritDoc
     */
    public function isMatch($requestString, $method)
    {
        if ($this->method != $method) {
            return false;
        }

        $paramMatches = [];
        $pregResult = preg_match($this->regexPattern, $requestString, $paramMatches);

        if ($pregResult === 1) {
            foreach ($this->params as $params) {
                $paramName = $params['name'];
                if (!$params['optional'] && !isset($paramMatches[$paramName])) {
                    return false;
                }

                $this->requestParams[$paramName] = $paramMatches[$paramName];
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function dispatch()
    {
        return [
            'path' => $this->path,
            'method' => $this->method,
            'handler' => $this->handler,
            'requestParams' => $this->requestParams,
        ];
    }

}
