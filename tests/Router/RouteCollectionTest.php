<?php
namespace tests\Router;

use Tuum\Router\RouteCollector;
use Tuum\Router\Router;

require_once(__DIR__ . '/../autoloader.php');

class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouteCollector
     */
    protected $routes;

    /**
     * @var Router
     */
    protected $router;

    function setup()
    {
        $this->router = new Router();
        $this->routes = $this->router->getRouting();
    }

    function test0()
    {
        $this->assertEquals('Tuum\Router\RouteCollector', get_class($this->routes));
    }

    /**
     * @test
     */
    function collector_adds_routes_to_parent_router()
    {
        $this->routes->addRoute('test', 'path', 'tested');
        $matched = $this->router->match('path', 'test');
        $this->assertEquals(['/path', 'method' => 'test'], $matched->params());
        $this->assertEquals('Tuum\Router\Route', get_class($matched));
    }

    /**
     * @test
     */
    function collector_get_post_put_delete_any_adds_method()
    {
        $this->routes->get('get', 'got');
        $this->routes->any('any', 'thing');
        $this->routes->put('put', 'putted');
        $this->routes->post('post', 'posted');
        $this->routes->delete('delete', 'deleted');

        $this->assertNotEmpty($this->router->match('/get', 'get'));
        $this->assertNotEmpty($this->router->match('/any'));
        $this->assertNotEmpty($this->router->match('/put', 'put'));
        $this->assertNotEmpty($this->router->match('/post', 'post'));
        $this->assertNotEmpty($this->router->match('/delete', 'delete'));
        $this->assertEmpty($this->router->match('/post', 'get'));
    }

    /**
     * @test
     */
    function collector_with_default_sets_default_in_handler()
    {
        $this->routes->group([
            'pattern' => 'test/',
            'handle' => 'tested/'
        ], function($routes) {
            /** @var RouteCollector $routes */
            $routes->get('get', 'got');
        });
        $matched = $this->router->match('/test/get', 'get');
        $this->assertEquals('Tuum\Router\Route', get_class($matched));
        $this->assertEquals('/test/get', $matched->path());
        $this->assertEquals('get', $matched->method());
    }
}