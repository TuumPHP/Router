<?php
namespace tests\Router;

use Tuum\Router\Matcher;
use Tuum\Router\Router;

require_once(__DIR__ . '/../autoloader.php');

class RouterTest extends \PHPUnit_Framework_TestCase
{
    function test0()
    {
        $this->assertEquals('Tuum\Router\Router', get_class(new Router()));
    }

    /**
     * @test
     */
    function constructor_takes_array_of_patterns_and_handlers()
    {
        $routes = [
            '/path/test'    => 'tested',
            '/more/array'   => ['more' => 'done'],
            '/this/closure' => function () {
                return 'closure';
            },
        ];
        $router = new Router($routes);

        $found = ['tested', ['/path/test', 'method' => '']];
        $this->assertEquals($found, $router->match('/path/test'));

        $found = [
            ['more' => 'done'],
            ['/more/array', 'method' => '']
        ];
        $this->assertEquals($found, $router->match('/more/array'));

        $matched = $router->match('/this/closure');
        $closure = $matched[0];
        $this->assertEquals('closure', $closure());

        $this->assertEquals(null, $router->match('/bad/path'));
    }

    /**
     * @test
     */
    function addRoute_adds_new_pattern()
    {
        $router = new Router();
        $router->addRoute('/path/test', 'tested');
        $this->assertEquals(['tested', ['/path/test', 'method' => '']], $router->match('/path/test'));
    }

    /**
     * @test
     */
    function getRouting_returns_RouteCollection()
    {
        $router = new Router();
        $this->assertEquals('Tuum\Router\RouteCollector', get_class($router->getRouting()));
    }

    /**
     * @test
     */
    function getReverseRoute_returns_ReverseRoute()
    {
        $router = new Router();
        $this->assertEquals('Tuum\Router\ReverseRoute', get_class($router->getReverseRoute()));
    }

    /**
     * @test
     */
    function router_returns_handler_if_Route_handler_is_given()
    {
        $handler = new \stdClass();
        $handler->name = 'named';
        $router = new Router();
        $router->addRoute('/path/test', $handler);
        $matched = $router->match('/path/test');
        $this->assertEquals($handler, $matched[0]);
        $this->assertSame($handler, $matched[0]);
    }

    /**
     * @test
     */
    function returns_matched_method()
    {
        $do = function($path) {
            $routers = [
                'get:/path',
                'get:/path/create',
                'post:/path',
                'get:/path/{id}',
                'get:/path/{id}/edit',
                'put:/path/{id}',
                'delete:/path/{id}',
            ];
            $methods = [];
            foreach($routers as $r) {
                $matched = Matcher::verify($r, $path, '*');
                if($matched && isset($matched['method'])) {
                    $methods[] = $matched['method'];
                }
            }
            return $methods;
        };

        $methods = $do( '/path', '*');
        $this->assertEquals(['get', 'post'], $methods);

        $methods = $do( '/path/1234', '*');
        $this->assertEquals(['get', 'put', 'delete'], $methods);
    }
}
