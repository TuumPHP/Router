<?php
namespace Tuum\Router\Tuum;

use Tuum\Router\Route;
use Tuum\Router\ReverseRouteInterface;
use Tuum\Router\RouterInterface;
use Tuum\Router\RouteCollector;
use Tuum\Router\Router as BaseRouter;

class Router implements RouterInterface
{
    /**
     * @var BaseRouter
     */
    protected $router;

    /**
     * @param BaseRouter $router
     */
    protected function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * @return Router
     */
    public static function forge()
    {
        return new self(new BaseRouter());
    }

    /**
     * matches against $request.
     * returns matched result, or false if not matched.
     *
     * @param string $path
     * @param string $method
     * @return null|Route
     */
    public function match($path, $method)
    {
        $found  = $this->router->match($path, $method);
        if (!$found) {
            return null;
        }
        return new Route($found[0]->data);
    }

    /**
     * get router to set routes.
     * returns various router object.
     *
     * @return RouteCollector
     */
    public function getRouting()
    {
        return $this->router->getRouting();
    }

    /**
     * @return ReverseRouteInterface
     */
    public function getReverseRoute()
    {
        return new NamedRoute($this->router->getReverseRoute());
    }
}