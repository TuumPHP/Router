<?php
namespace Tuum\Router\Tuum;

use Tuum\Router\ReverseRouteInterface;
use Tuum\Router\RouterInterface;
use Tuum\Web\App;
use Tuum\Web\Http\Request;

class Router implements RouterInterface
{
    /**
     * array of [ 'pattern' => $handler, ... ]
     *
     * @var array
     */
    protected $routes = [];

    /**
     * @param array $routes
     */
    public function __construct($routes=[])
    {
        $this->routes = $routes;
    }

    /**
     * matches against $request.
     * returns matched result, or false if not matched.
     *
     * @param Request $request
     * @return array
     */
    public function match($request)
    {
        $path   = $request->getPathInfo();
        $method = $request->getMethod();
        foreach($this->routes as $pattern => $handler) {

            if ($params = Matcher::verify($path, $method, $pattern)) {
                $request->setAttribute(App::CONTROLLER,  $handler);
                $request->setAttribute(App::ROUTE_PARAM, $params);
                return [$handler, $params];
            }
        }
        return [];
    }

    /**
     * get router to set routes.
     * returns various router object.
     *
     * @return mixed
     */
    public function getRouting()
    {
        // TODO: Implement getRouting() method.
    }

    /**
     * @param Request $request
     * @return ReverseRouteInterface
     */
    public function getReverseRoute($request)
    {
        // TODO: Implement getReverseRoute() method.
    }
}