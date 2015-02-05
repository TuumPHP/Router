<?php
namespace Tuum\Router\PhRouter;

use Phroute\Dispatcher;
use Phroute\Route;
use Phroute\RouteCollector;
use Psr\Http\Message\RequestInterface;
use Tuum\Router\ReverseRouteInterface;
use Tuum\Router\RouterInterface;
use Tuum\Router\Route as RouteInfo;

class HttpRouteNotFoundException extends \Exception
{
}

class HttpMethodNotAllowedException extends \Exception
{
}

class Router implements RouterInterface
{
    /**
     * @var array
     */
    protected $staticRouteMap = [];

    /**
     * @var array
     */
    protected $variableRouteData = [];

    /**
     * @var RouteCollector
     */
    protected $route;

    /**
     * @param RouteCollector $route
     */
    protected function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * @return Router
     */
    public static function forge()
    {
        return new self( new RouteCollector());
    }

    /**
     * @return RouteCollector
     */
    public function getRouting()
    {
        return $this->route;
    }

    /**
     * @param RequestInterface $request
     * @return Route|null
     */
    public function match($request)
    {
        list($this->staticRouteMap, $this->variableRouteData) = $this->route->getData();
        $method = $request->getMethod();
        $uri    = $request->getUri()->getPath();
        try {
            list($handler, $filters, $vars) = $this->dispatchRoute($method, trim($uri, '/'));
        } catch( HttpRouteNotFoundException $e ) {
            return null;
        } catch( HttpMethodNotAllowedException $e ) {
            return null;
        }
        return new RouteInfo(['handle' => $handler, 'before' => $filters, 'params' => $vars ]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @return array
     */
    protected function matchRoute($method, $uri)
    {
        list($handler, $filters, $vars) = $this->dispatchRoute($method, trim($uri, '/'));
        return [$handler, $filters, $vars];
    }

    /**
     * Perform the route dispatching. Check static routes first followed by variable routes.
     *
     * @param $httpMethod
     * @param $uri
     * @return mixed
     * @throws HttpRouteNotFoundException
     */
    private function dispatchRoute($httpMethod, $uri)
    {
        if (isset($this->staticRouteMap[$uri])) {
            return $this->dispatchStaticRoute($httpMethod, $uri);
        }
        return $this->dispatchVariableRoute($httpMethod, $uri);
    }

    /**
     * Handle the dispatching of static routes.
     *
     * @param $httpMethod
     * @param $uri
     * @return mixed
     * @throws HttpMethodNotAllowedException
     */
    private function dispatchStaticRoute($httpMethod, $uri)
    {
        $routes = $this->staticRouteMap[$uri];

        if (!isset($routes[$httpMethod])) {
            $httpMethod = $this->checkFallbacks($routes, $httpMethod);
        }
        return $routes[$httpMethod];
    }

    /**
     * Handle the dispatching of variable routes.
     *
     * @param $httpMethod
     * @param $uri
     * @return mixed
     * @throws HttpRouteNotFoundException
     */
    private function dispatchVariableRoute($httpMethod, $uri)
    {
        foreach ($this->variableRouteData as $data) {
            if (!preg_match($data['regex'], $uri, $matches)) {
                continue;
            }

            $count = count($matches);
            while (!isset($data['routeMap'][$count++])) {
                ;
            }

            $routes = $data['routeMap'][$count - 1];

            if (!isset($routes[$httpMethod])) {
                $httpMethod = $this->checkFallbacks($routes, $httpMethod);
            }

            foreach (array_values($routes[$httpMethod][2]) as $i => $varName) {
                if (!isset($matches[$i + 1]) || $matches[$i + 1] === '') {
                    unset($routes[$httpMethod][2][$varName]);
                } else {
                    $routes[$httpMethod][2][$varName] = $matches[$i + 1];
                }
            }

            return $routes[$httpMethod];
        }

        throw new HttpRouteNotFoundException('Route ' . $uri . ' does not exist');
    }

    /**
     * Check fallback routes: HEAD for GET requests followed by the ANY attachment.
     *
     * @param $routes
     * @param $httpMethod
     * @throws HttpMethodNotAllowedException
     */
    private function checkFallbacks($routes, $httpMethod)
    {
        $additional = array(Route::ANY);

        if ($httpMethod === Route::HEAD) {
            $additional[] = Route::GET;
        }

        foreach ($additional as $method) {
            if (isset($routes[$method])) {
                return $method;
            }
        }

        $this->matchedRoute = $routes;

        throw new HttpMethodNotAllowedException('Allow: ' . implode(', ', array_keys($routes)));
    }

    /**
     * @return ReverseRouteInterface
     */
    public function getReverseRoute()
    {
        return new NamedRoute($this->route);
    }
}