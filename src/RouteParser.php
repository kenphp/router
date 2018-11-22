<?php

namespace Ken\Router;

/**
 * @author Muhammad Safri Juliardi [ardi93@gmail.com]
 */
class RouteParser
{
    const ROUTE_PATTERN = '~(?<route>(?:/[a-zA-Z0-9-]*)+)~is';
    const OPTIONAL_OPEN_PARAMS_PATTERN = '(?<optional>(?:\\[|\\[/)*)';
    const OPEN_PARAMS_PATTERN = '(?:\\{){1}';
    const NAME_PARAMS_PATTERN = '(?<name>(?:[a-z][a-z]+))';
    const CLOSE_PARAMS_PATTERN = '(?:\\}){1}';
    const OPTIONAL_CLOSE_PARAMS_PATTERN = '(?:\\])*';
    const DATA_TYPE_PATTERN = '\w+';

    /**
     * @var string
     */
    protected $paramsPattern;

    /**
     * Constructs RouteParser object
     */
    public function __construct()
    {
        $this->paramsPattern = '~' . self::OPTIONAL_OPEN_PARAMS_PATTERN;
        $this->paramsPattern .= self::OPEN_PARAMS_PATTERN;
        $this->paramsPattern .= self::NAME_PARAMS_PATTERN;
        $this->paramsPattern .= self::CLOSE_PARAMS_PATTERN;
        $this->paramsPattern .= self::OPTIONAL_CLOSE_PARAMS_PATTERN . '~is';
    }

    /**
     * Parses a route string defined by user
     * @param  string $routeString A route string that may contains parameters definition
     * @return array An array contains parsed route path and parameters
     */
    public function parse($routeString)
    {
        $result = $this->parseRoute($routeString);

        if (!empty($result)) {
            $result = $this->parseParams($routeString, $result);
        }

        return $result;
    }

    /**
     * Parses a route path from route string defined by user
     * @param  string $routeString A route string that may contains parameters definition
     * @return array An array contains parsed route path
     */
    protected function parseRoute($routeString)
    {
        $matchesRoute = [];
        $result = [];

        $regexMatch = preg_match(self::ROUTE_PATTERN, $routeString, $matchesRoute);

        if ($regexMatch === 1) {
            $result = [
                'path' => $matchesRoute['route'],
                'regexPattern' => $routeString,
            ];
        }

        return $result;
    }

    /**
     * Parses parameters from route strinng
     * @param  string $routeString A route string that may contains parameters definition
     * @param  array $parseRouteResult An array result from self::parseRoute() function
     * @return array
     */
    protected function parseParams($routeString, $parseRouteResult)
    {
        $matchesParams = [];
        $parseRouteResult['params'] = [];

        $regexMatch = preg_match_all($this->paramsPattern, $routeString, $matchesParams, PREG_SET_ORDER);

        if ($regexMatch !== false) {
            foreach ($matchesParams as $key => $arrMatches) {
                $pattern = $arrMatches[0];
                $optional = !empty($arrMatches['optional']);
                $paramName = $arrMatches['name'];

                $parseRouteResult['params'][$key] = [
                    'pattern' => $pattern,
                    'name' => $paramName,
                    'optional' => $optional,
                ];

                $replacement = "(?<$paramName>" . self::DATA_TYPE_PATTERN . ")";

                if ($optional) {
                    $replacement = "((/)*$replacement){0,1}";
                }
                $parseRouteResult['regexPattern'] = str_replace($pattern, $replacement, $parseRouteResult['regexPattern']);
            }
        }

        $parseRouteResult['regexPattern'] = '~^' . $parseRouteResult['regexPattern'] . '$~is';

        return $parseRouteResult;
    }
}
