<?php
namespace Test;

class RouterTest extends \Codeception\Test\Unit
{
    /**
     * @var \tests\
     */
    protected $tester;

    /**
     * @var \Ken\Router\Router
     */
    protected $router;

    protected function _before()
    {
        $this->router = new \Ken\Router\Router();
    }

    protected function _after()
    {
    }

    public function testGetMethod() {
        $this->router->get('/index', 'HomeController::index');

        $routeObject = $this->router->resolve('/index', 'GET');

        $this->assertEquals('HomeController::index', $routeObject['handler']);
    }

    public function testHeadMethod() {
        $this->router->head('/index', 'HomeController::index');

        $routeObject = $this->router->resolve('/index', 'HEAD');

        $this->assertEquals('HomeController::index', $routeObject['handler']);
    }

    public function testPostMethod() {
        $this->router->post('/create', 'HomeController::create', ['middleware' => ['auth']]);

        $routeObject = $this->router->resolve('/create', 'POST');

        $this->assertEquals('HomeController::create', $routeObject['handler']);
        $this->assertEquals('auth', $routeObject['middleware'][0]);
    }

    public function testPutMethod() {
        $this->router->put('/update/{id}', 'HomeController::update', ['middleware' => ['auth']]);

        $routeObject = $this->router->resolve('/update/1', 'PUT');

        $this->assertEquals('HomeController::update', $routeObject['handler']);
        $this->assertEquals('auth', $routeObject['middleware'][0]);
    }

    public function testDeleteMethod() {
        $this->router->delete('/delete/{id}', 'HomeController::delete', ['middleware' => ['auth']]);

        $routeObject = $this->router->resolve('/delete/1', 'DELETE');

        $this->assertEquals('HomeController::delete', $routeObject['handler']);
        $this->assertEquals('auth', $routeObject['middleware'][0]);
    }

    public function testGroupMethod() {
        $this->router->group('/users', function () {
            $this->router->get('/{i}', 'UserController::get');
        }, ['middleware' => ['auth']]);

        $routeObject = $this->router->resolve('/users/1', 'GET');

        $this->assertEquals('UserController::get', $routeObject['handler']);
        $this->assertEquals('auth', $routeObject['middleware'][0]);
    }
}
