<?php
namespace tests\Router;

use Tuum\Router\Handler;
use Tuum\Router\ReverseRoute;
use Tuum\Router\Router;

require_once(__DIR__ . '/../autoloader.php');

class ReverseRouteTest extends \PHPUnit_Framework_TestCase
{
    function test0()
    {
        $rev = new ReverseRoute([]);
        $this->assertEquals('Tuum\Router\ReverseRoute', get_class($rev));
    }

    /**
     * @test
     */
    function basic_reverse_testing()
    {
        $router = new Router([
            'tested' => ['name' =>'test'],
            'get:/more' => ['name' =>'more'],
            'handled' => (new Handler('object'))->name('handler'),
            'coverage' => '100%'
        ]);
        $rev = (new ReverseRoute())->addRouter($router);
        $this->assertEquals('tested', $rev->generate('test'));
        $this->assertEquals('/more', $rev->generate('more'));
        $this->assertEquals('handled', $rev->generate('handler'));
    }

    /**
     * @test
     */
    function reverseRoute_returns_without_setting_one()
    {
        $router = new Router([
            'tested' => ['name' =>'test'],
            'get:/more' => ['name' =>'more'],
            'handled' => (new Handler('object'))->name('handler'),
            'coverage' => '100%'
        ]);
        $rev = $router->getReverseRoute();
        $this->assertEquals('tested', $rev->generate('test'));
        $this->assertEquals('/more', $rev->generate('more'));
        $this->assertEquals('handled', $rev->generate('handler'));
    }

    /**
     * @test
     */
    function reverseRoute_set_for_multiple_routers()
    {
        $router1 = new Router([
            'tested' => ['name' =>'test'],
            'get:/more' => ['name' =>'more'],
        ]);
        $router2 = new Router([
            'handled' => (new Handler('object'))->name('handler'),
            'coverage' => '100%'
        ]);
        $rev = new ReverseRoute();
        $router1->setReverseRoute($rev);
        $router2->setReverseRoute($rev);
        $this->assertEquals('tested', $rev->generate('test'));
        $this->assertEquals('/more', $rev->generate('more'));
        $this->assertEquals('handled', $rev->generate('handler'));
    }

    /**
     * @test
     */
    function route_with_parameter()
    {
        $router = new Router([
            'test/{id}' => ['name' =>'test']
        ]);
        $rev = (new ReverseRoute())->addRouter($router);
        $this->assertEquals('test/id', $rev->generate('test'));
        $this->assertEquals('test/12', $rev->generate('test', ['id' => '12']));
    }

    /**
     * @test
     */
    function route_with_asterisk()
    {
        $router = new Router([
            'test/*' => ['name' =>'test']
        ]);
        $rev = (new ReverseRoute())->addRouter($router);
        $this->assertEquals('test/', $rev->generate('test'));
        $this->assertEquals('test/12', $rev->generate('test', ['id' => '12']));
    }
}
