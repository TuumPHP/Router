<?php
namespace tests\Router;

use Tuum\Router\Handler;

require_once(__DIR__ . '/../autoloader.php');

/**
 * @property mixed|null handle
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{
    function test0()
    {
        $this->assertEquals('Tuum\Router\Handler', get_class(new Handler('test')));
    }

    /**
     * @test
     */
    function handler_sets_data()
    {
        $handler = new Handler('handle-name');
        $handler
            ->name('named')
            ->after('after1')
            ->after('after2')
            ->before('before1')
            ->before('before2')
            ->params(['id' => '123'])
        ;
        $data = [
            'handle' => 'handle-name',
            'name'   => 'named',
            'after'  => ['after1', 'after2'],
            'before' => ['before1', 'before2'],
            'params' => [ 'id' => '123' ],
        ];
        $this->assertEquals($data, $handler->data);
    }

    /**
     * @test
     */
    function default_value_adds_values()
    {
        $default = [
            'handle' => '/test/',
            'name'   => 'name-',
            'after' => ['after0'],
            'before' => ['before0']
        ];
        $handler = new Handler('handle-name', $default);
        $handler
            ->name('named')
            ->after('after1')
            ->before('before1')
            ;
        $data = [
            'handle' => '/test/handle-name',
            'name'   => 'name-named',
            'after'  => ['after0', 'after1'],
            'before' => ['before0', 'before1'],
        ];
        $this->assertEquals($data, $handler->data);
    }

    /**
     * @test
     */
    function getting_properties_from_handler()
    {
        $handler = new Handler('property');
        $handler->name('named')
            ->after('after')
            ->before('before')
        ;
        $this->assertEquals( 'property', $handler->handle);
        $this->assertEquals( 'named', $handler->name);
        $this->assertEquals( ['before'], $handler->before);
        $this->assertEquals( ['after'], $handler->after);
    }

    /**
     * @test
     */
    function getting_properties_not_present()
    {
        $handler = new Handler('empty');
        $this->assertEquals( 'empty', $handler->handle);
        $this->assertEquals( null, $handler->name);
        $this->assertEquals( [], $handler->before);
        $this->assertEquals( [], $handler->after);
    }

    /**
     * @test
     */
    function getting_non_existent_property_returns_null()
    {
        $handler = new Handler('none');
        $this->assertEquals(null, $handler->none);
    }
}