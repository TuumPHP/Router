<?php
namespace Tuum\Router\FastRoute;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Tuum\Router\Route;
use Tuum\Router\ReverseRouteInterface;
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
    public function getRouting()
    {
        return $this->routes;
    }

    /**
     * @param string $path
     * @param string $method
     * @return null|Route
     */
    public function match($path, $method)
    {
        $dispatcher = new Dispatcher($this->routes->getData());
        $routeInfo  = $dispatcher->dispatch($method, $path);

        $this->matchResult = $routeInfo[0];
        switch ($this->matchResult) {

            case \FastRoute\Dispatcher::NOT_FOUND:
                return [];

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $this->allowedMethods = $routeInfo[1];
                return [];

            case \FastRoute\Dispatcher::FOUND:
                return new Route([
                    'handle' => $routeInfo[1],
                    'params' => $routeInfo[2],
                ]);

        }
        throw new \RuntimeException();
    }

    /**
     * @return ReverseRouteInterface
     */
    public function getReverseRoute()
    {
        return null;
    }
}
