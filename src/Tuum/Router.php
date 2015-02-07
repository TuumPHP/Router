<?php
namespace Tuum\Router\Tuum;

use Psr\Http\Message\RequestInterface;
use Tuum\Router\Route;
use Tuum\Router\ReverseRouteInterface;
use Tuum\Router\RouterInterface;
use Tuum\Routing\Router as BaseRouter;

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
     * @param RequestInterface $request
     * @return mixed|Route
     */
    public function match($request)
    {
        $path   = $request->getUri()->getPath();
        $method = $request->getMethod();
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
     * @return mixed
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