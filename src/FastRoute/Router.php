<?php
namespace Tuum\Router;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Tuum\Web\App;
use Tuum\Web\Http\Request;
use Tuum\Web\NamedRoutesInterface\RouteNamesInterface;
use Tuum\Web\ServiceInterface\RouterInterface;

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
                $request->keep(App::CONTROLLER, $handler);
                $request->keep(App::ROUTE_PARAM, $vars);
                return [$handler, $vars];

        }
        throw new \RuntimeException();
    }

    /**
     * @return RouteNamesInterface
     */
    public function namedRoutes()
    {
        return null;
    }
}
