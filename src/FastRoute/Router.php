<?php
namespace Tuum\Router\FastRoute;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Tuum\Web\App;
use Tuum\Web\Http\Request;
use Tuum\Router\RouteNamesInterface;
use Tuum\Router\RouterInterface;

class Router implements RouterInterface
{
    /**
     * @var RouteCollector
     */
    protected $routes;

    /**
     * @var string
     */
    public $matchResult;

    /**
     * @var array
     */
    public $allowedMethods = [];

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
        return new self(new RouteCollector(
            new Std, new GroupCountBased
        ));
    }

    /**
     * @return RouteCollector
     */
    public function router()
    {
        return $this->routes;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function match($request)
    {
        $dispatcher = new Dispatcher($this->routes->getData());
        $routeInfo  = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        $this->matchResult = $routeInfo[0];
        switch ($this->matchResult) {

            case \FastRoute\Dispatcher::NOT_FOUND:
                return [];

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $this->allowedMethods = $routeInfo[1];
                return [];

            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars    = $routeInfo[2];
                $request->setAttribute(App::CONTROLLER, $handler);
                $request->setAttribute(App::ROUTE_PARAM, $vars);
                return [$handler, $vars];

        }
        throw new \RuntimeException();
    }

    /**
     * @param Request $request
     * @return RouteNamesInterface
     */
    public function namedRoutes($request)
    {
        return null;
    }
}
