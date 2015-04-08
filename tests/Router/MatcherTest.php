<?php
namespace tests\Router;

use Tuum\Router\Matcher;

require_once(__DIR__ . '/../autoloader.php');

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function match_simple_route()
    {
        $match = Matcher::verify( '/path/to', '/path/to' );
        $this->assertEquals('/path/to', $match[0]);
    }

    /**
     * @test
     */
    function no_method_in_pattern_ignores_input_method()
    {
        $match = Matcher::verify( '/path/to', '/path/to', 'weired-method' );
        $this->assertEquals('/path/to', $match[0]);
    }

    /**
     * @test
     */
    function case_for_match_failure()
    {
        $match = Matcher::verify( '/path/',   '/path/to' );
        $this->assertEmpty($match);

        $match = Matcher::verify( '/path/{id}/to', '/path/more/to/it' );
        $this->assertEmpty($match);

        $match = Matcher::verify( 'get:/path/{id}', '/path/1234', 'PUT' );
        $this->assertEmpty($match);
    }

    /**
     * @test
     */
    function route_with_parameter()
    {
        $match = Matcher::verify( '/path/{id}', '/path/to' );
        $this->assertEquals('to', $match['id']);

        $match = Matcher::verify( 'path/{id}',  '/path/to' );
        $this->assertEquals('to', $match['id']);

        $match = Matcher::verify( '/path/{id}', 'path/to' );
        $this->assertEquals('to', $match['id']);

        $match = Matcher::verify( '/path/{id}/to', '/path/more/to' );
        $this->assertEquals('more', $match['id']);
    }

    /**
     * @test
     */
    function pattern_with_method()
    {
        $match = Matcher::verify( 'get:/path/to', '/path/to', 'get' );
        $this->assertEquals('/path/to', $match[0]);

        $match = Matcher::verify( 'get:/path/{id}', '/path/1234', 'GET' );
        $this->assertEquals('1234', $match['id']);

        $match = Matcher::verify( 'put:/path/{id}', '/path/1234', 'PUT' );
        $this->assertEquals('1234', $match['id']);
    }

    /**
     * @test
     */
    function pattern_containing_colon()
    {
        $match = Matcher::verify( 'get:/path:/to', '/path:/to', 'get' );
        $this->assertEquals('/path:/to', $match[0]);

        $match = Matcher::verify( '/path:/to', '/path:/to' );
        $this->assertEquals('/path:/to', $match[0]);
    }

    /**
     * @test
     */
    function try_closure_style()
    {
        $matcher = new Matcher;
        $match = $matcher( 'get:/path/to', '/path/to', 'get' );
        $this->assertEquals('/path/to', $match[0]);
    }

    /**
     * @test
     */
    function route_without_starting_slash()
    {
        $matcher = new Matcher;
        $match = $matcher( '/1234', '1234', 'get' );
        $this->assertEquals('/1234', $match[0]);
    }

    /**
     * @test
     */
    function remaining_route()
    {
        $match = Matcher::verify( 'get:/path/resource/{*}', '/path/resource/1234', 'get' );
        $this->assertEquals('/path/resource/1234', $match[0]);
        $this->assertEquals('1234', $match['trailing']);
        $this->assertEquals('/path/resource/', $match['matched']);

        $match = Matcher::verify( 'get:/path/file.{*}', '/path/file.ext', 'get' );
        $this->assertEquals('/path/file.ext', $match[0]);
        $this->assertEquals('/path/file.', $match['matched']);
    }

    /**
     * @test
     */
    function param_types()
    {
        $match = Matcher::verify( '/path/{id:i}', '/path/123' );
        $this->assertEquals('123', $match['id']);

        $match = Matcher::verify( '/path/{id:i}', '/path/edit' );
        $this->assertEmpty($match);

        $match = Matcher::verify( '/path/(?P<id>[0-9]+)', '/path/123' );
        $this->assertEquals('123', $match['id']);
    }

    /**
     * @test
     */
    function matching_with_star_exactly_with_pattern()
    {
        $match = Matcher::verify( '/path{*}', '/path' );
        $this->assertEquals('/path', $match['matched']);
        $this->assertEquals('', $match['trailing']);
    }

    /**
     * @test
     */
    function matching_simple_asterisk()
    {
        $match = Matcher::verify( '/path*', '/path' );
        $this->assertNotEmpty($match);
        $this->assertEquals('/path', $match[0]);
        $this->assertEquals('', $match[1]);

        $match = Matcher::verify( '/path*', '/path/to' );
        $this->assertNotEmpty($match);
        $this->assertEquals('/path/to', $match[0]);
        $this->assertEquals('/to', $match[1]);
    }

    /**
     * @test
     */
    function method_asterisk_ignores_method()
    {
        $match = Matcher::verify( 'get:/path/{id}', '/path/1234' );
        $this->assertEquals([], $match);
        $match = Matcher::verify( 'get:/path/{id}', '/path/1234', 'PUT' );
        $this->assertEquals([], $match);
        $match = Matcher::verify( 'get:/path/{id}', '/path/1234', 'GET' );
        $this->assertEquals('1234', $match['id']);
        $match = Matcher::verify( 'get:/path/{id}', '/path/1234', '*' );
        $this->assertEquals('1234', $match['id']);
    }
}

