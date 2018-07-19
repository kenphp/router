<?php
namespace Test;

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

    protected function _before()
    {
        $this->routeObject = new \Ken\Router\Route();
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
        $this->expectExceptionMessage("Method 'asdf' not allowed");

        $this->routeObject->setMethod("asdf");
    }

    public function testSetPath()
    {
        $this->routeObject->setPath("/home");
        $this->assertEquals($this->routeObject->getPath(), "/home");
    }

    public function testIsRouteMatch()
    {
        $this->routeObject->setMethod('GET');
        $this->routeObject->setPath('/home');

        $this->assertTrue($this->routeObject->isMatch('/home', 'GET'));

        $this->assertFalse($this->routeObject->isMatch('/home', 'POST'));
        $this->assertFalse($this->routeObject->isMatch('/', 'POST'));
    }

    public function testIsRouteMatchRegex()
    {
        $this->specify("Test integer parameter", function () {
            $this->routeObject->setPath('/products/{int:id}');

            $this->assertTrue($this->routeObject->isMatch('/products/1', 'GET'));
            $this->assertFalse($this->routeObject->isMatch('/products/index', 'GET'));
            $this->assertFalse($this->routeObject->isMatch('/products/1.12', 'GET'));
        });

        $this->specify("Test string parameter", function () {
            $this->routeObject->setPath('/products/{string:id}');

            $this->assertTrue($this->routeObject->isMatch('/products/index', 'GET'));
            $this->assertTrue($this->routeObject->isMatch('/products/1', 'GET'));
            $this->assertFalse($this->routeObject->isMatch('/products', 'GET'));
        });

        $this->specify("Test optional parameter", function () {
            $this->routeObject->setPath('/products[/{string:id}]');

            $this->assertTrue($this->routeObject->isMatch('/products', 'GET'));
            $this->assertTrue($this->routeObject->isMatch('/products/index', 'GET'));
            $this->assertFalse($this->routeObject->isMatch('/products/1#!@!!#$"""', 'GET'));
        });
    }

    public function testSetHandlerException()
    {
        $this->expectException(\Error::class);
        $this->routeObject->setHandler('Class::method');
    }

    public function testGetPredefinedRegex()
    {
    }
}
