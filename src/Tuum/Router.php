<?php
namespace Tuum\Router\Tuum;

use Tuum\Router\ReverseRouteInterface;
use Tuum\Router\RouterInterface;
use Tuum\Routing\Router as BaseRouter;
use Tuum\Web\App;
use Tuum\Web\Http\Request;

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
     * @param Request $request
     * @return mixed
     */
    public function match($request)
    {
        $path   = $request->getPathInfo();
        $method = $request->getMethod();
        $found  = $this->router->match($path, $method);
        if (!$found) {
            return null;
        }
        $request->setAttribute(App::CONTROLLER, $found[0]['handle']);
        $request->setAttribute(App::ROUTE_PARAM, $found[1]);
        return $found;
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
     * @param Request $request
     * @return ReverseRouteInterface
     */
    public function getReverseRoute($request)
    {
        return new NamedRoute($this->router->getReverseRoute());
    }
}