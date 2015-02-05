<?php
namespace Tuum\Router\Aura;

use Aura\Router\DefinitionFactory;
use Aura\Router\Map;
use Aura\Router\Route;
use Aura\Router\RouteFactory;
use Psr\Http\Message\RequestInterface;
use Tuum\Router\ReverseRouteInterface;
use Tuum\Router\RouterInterface;

class Router implements RouterInterface
{
    /**
     * @var Map
     */
    protected $routes;

    /**
     * server information (i.e. $_SERVER)
     *
     * @var array
     */
    protected $server;

    /**
     * @param $routes
     */
    protected function __construct($routes, $server=[])
    {
        $this->routes = $routes;
        $this->server = $server ?: $_SERVER;
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
     * @param RequestInterface $request
     * @return Route|null
     */
    public function match($request)
    {
        $path  = parse_url( (string) $request->getUri(), PHP_URL_PATH);
        $route = $this->routes->match($path, $this->server);
        if(!$route) return null;
        return new \Tuum\Router\Route([
            'handle' => $route->__get('name'),
            'name'   => $route->__get('name'),
            'params' => $route->__get('params'),
        ]);
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
     * @return ReverseRouteInterface
     */
    public function getReverseRoute()
    {
        return new NamedRoute($this->routes);
    }
}