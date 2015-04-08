<?php
namespace tests\Router;

use Tuum\Router\Handler;
use Tuum\Router\ReverseRoute;

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
        $rev = new ReverseRoute([
            'tested' => ['name' =>'test'],
            'get:/more' => ['name' =>'more'],
            'handled' => (new Handler('object'))->name('handler'),
            'coverage' => '100%'
        ]);
        $this->assertEquals('tested', $rev->generate('test'));
        $this->assertEquals('/more', $rev->generate('more'));
        $this->assertEquals('handled', $rev->generate('handler'));
    }

    /**
     * @test
     */
    function route_with_parameter()
    {
        $rev = new ReverseRoute([
            'test/{id}' => ['name' =>'test']
        ]);
        $this->assertEquals('test/id', $rev->generate('test'));
        $this->assertEquals('test/12', $rev->generate('test', ['id' => '12']));
    }

    /**
     * @test
     */
    function route_with_asterisk()
    {
        $rev = new ReverseRoute([
            'test/*' => ['name' =>'test']
        ]);
        $this->assertEquals('test/', $rev->generate('test'));
        $this->assertEquals('test/12', $rev->generate('test', ['id' => '12']));
    }
}
