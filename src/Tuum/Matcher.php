<?php
namespace Tuum\Router\Tuum;

/**
 * Class Matcher
 * @package Tuum\Router\Tuum
 *
 * pattern:
 *
 *   [method-name]:/path/{name}/file
 *
 * - method-name maybe omitted. if omitted, any method will match.
 * - {name} is variable whose name must be [_0-9a-zA-Z]+
 *   and its value must be [-_0-9a-zA-Z]+
 *
 * if you do not like the static method, try,
 *
 *         (new Matcher)($route, $path, $method);
 *
 * this should do, if PHP can parse it...
 *
 */
class Matcher
{
    /**
     * @param string $route
     * @param string $path
     * @param string $method
     * @return array
     */
    public function __invoke($route, $path, $method=null)
    {
        return static::verify($route, $path, $method=null);
    }

    /**
     * @param string $route
     * @param string $path
     * @param string $method
     * @return array
     */
    public static function verify($route, $path, $method=null)
    {
        /*
         * check method comparison, if method is specified.
         */
        if (strpos($route, ':') !== false ) {
            list($routeMethod, $route) = explode( ':', $route, 2 );
            if (strcmp(strtolower($method), strtolower($routeMethod))) {
                return [];
            }
        }
        $route = '/'.ltrim($route, '/');
        $path  = '/'.ltrim($path, '/');
        /*
         * now verify route against the path.
         */
        // replace {name} => (?P<name>[-_0-9a-zA-Z]+)
        $route = preg_replace_callback( '/{([_0-9a-zA-Z]+)}/', function($match) {
            $name = $match[1]; // this should be the matched name.
            return "(?P<{$name}>[-_0-9a-zA-Z]+)";
        }, $route);
        // now match the route against the path.
        if (preg_match('#^'.$route.'$#', $path, $matches)) {
            return $matches;
        }
        return [];
    }
}