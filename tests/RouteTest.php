<?php
namespace Test;

use Ken\Router\RouteParser;

class RouteTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    /**
     * @var \tests\
     */
    protected $tester;

    /**
     * @var \Ken\Router\Route
     */
    protected $routeObject;

    /**
     * @var \Ken\Router\RouteParser
     */
    protected $routeParser;

    protected function _before()
    {
        $this->routeParser = new RouteParser();

        $routeConfig = $this->routeParser->parse('/users/{name}');
        $routeConfig['handler'] = 'Class::method';
        $routeConfig['method'] = 'GET';

        $this->routeObject = new \Ken\Router\Route($routeConfig);
    }

    protected function _after()
    {
    }

    public function testSetMethodAllowed()
    {
        $this->specify("Set method to 'GET'", function () {
            $this->routeObject->setMethod("GET");
            $this->assertEquals($this->routeObject->getMethod(), "GET");
        });

        $this->specify("Set method to 'POST'", function () {
            $this->routeObject->setMethod("POST");
            $this->assertEquals($this->routeObject->getMethod(), "POST");
        });

        $this->specify("Set method to 'PUT'", function () {
            $this->routeObject->setMethod("PUT");
            $this->assertEquals($this->routeObject->getMethod(), "PUT");
        });

        $this->specify("Set method to 'DELETE'", function () {
            $this->routeObject->setMethod("DELETE");
            $this->assertEquals($this->routeObject->getMethod(), "DELETE");
        });
    }

    public function testSetMethodException()
    {
        $this->expectException(\Ken\Router\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage("Method 'asdf' is not allowed");

        $this->routeObject->setMethod("asdf");
    }

    public function testSetPath()
    {
        $this->routeObject->setPath("/home");
        $this->assertEquals($this->routeObject->getPath(), "/home");
    }

    public function testIsRouteMatchRegex()
    {
        $this->specify("Test parameter", function () {
            $routeConfig = $this->routeParser->parse('/products/{id}');
            $this->routeObject->setPath($routeConfig['path']);
            $this->routeObject->setRegexPattern($routeConfig['regexPattern']);
            $this->routeObject->setParams($routeConfig['params']);

            $this->assertTrue($this->routeObject->isMatch('/products/index', 'GET'));
            $this->assertTrue($this->routeObject->isMatch('/products/1', 'GET'));
            $this->assertTrue($this->routeObject->isMatch('/products/1.1', 'GET'));
            $this->assertFalse($this->routeObject->isMatch('/products', 'GET'));
        });

        $this->specify("Test optional parameter", function () {
            $routeConfig = $this->routeParser->parse('/products[/{id}]');
            $this->routeObject->setPath($routeConfig['path']);
            $this->routeObject->setRegexPattern($routeConfig['regexPattern']);
            $this->routeObject->setParams($routeConfig['params']);

            $this->assertTrue($this->routeObject->isMatch('/products', 'GET'));
            $this->assertTrue($this->routeObject->isMatch('/products/index', 'GET'));
            $this->assertTrue($this->routeObject->isMatch('/products/1#!@!!#$"""', 'GET'));
        });
    }

    public function testSetHandlerException()
    {
        $this->routeObject->setHandler('Class::method');
        $this->assertTrue($this->routeObject->getHandler() == 'Class::method');
    }
}
