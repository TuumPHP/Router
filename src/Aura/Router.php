<?php
namespace Tuum\Router\Aura;

use Aura\Router\DefinitionFactory;
use Aura\Router\Map;
use Aura\Router\Route;
use Aura\Router\RouteFactory;
use Tuum\Web\Http\Request;
use Tuum\Router\ReverseRouteInterface;
use Tuum\Router\RouterInterface;

class Router implements RouterInterface
{
    /**
     * @var Map
     */
    protected $routes;

    /**
     * @param $routes
     */
    protected function __construct($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @return Router
     */
    public static function forge()
    {
        return new self(new Map(new DefinitionFactory, new RouteFactory));
    }

    /**
     * matches against $request.
     * returns matched result, or false if not matched.
     *
     * @param Request $request
     * @return Route
     */
    public function match($request)
    {
        $path  = parse_url( $request->getRequestUri(), PHP_URL_PATH);
        $route = $this->routes->match($path, $request->server->all());
        return $route;
    }

    /**
     * get router to set routes.
     * returns various router object.
     *
     * @return Map
     */
    public function getRouting()
    {
        return $this->routes;
    }

    /**
     * @param Request $request
     * @return ReverseRouteInterface
     */
    public function getReverseRoute($request)
    {
        return new NamedRoute($this->routes);
    }
}