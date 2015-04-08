<?php
namespace Tuum\Router;

class Router implements RouterInterface
{
    /**
     * array of [ 'pattern' => $handler, ... ]
     *
     * @var array
     */
    public $routes = [];

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
     * @param string $path
     * @param string $method
     * @return null|Route|array
     */
    public function match($path, $method=null)
    {
        foreach($this->routes as $pattern => $handler) {

            if ($params = Matcher::verify($pattern, $path, $method)) {
                if ($handler instanceof Handler) {
                    $handler = $handler->params($params);
                    $data    = $handler->data;
                    $data['path'] = $path;
                    $data['method'] = $method;
                    return new Route($data);
                }
                return [$handler, $params];
            }
        }
        return null;
    }

    /**
     * @param string $pattern
     * @param mixed  $handler
     */
    public function addRoute($pattern, $handler)
    {
        $this->routes[$pattern] = $handler;
    }

    /**
     * get router to set routes.
     * returns various router object.
     *
     * @return RouteCollector
     */
    public function getRouting()
    {
        return new RouteCollector($this);
    }

    /**
     * @return ReverseRoute
     */
    public function getReverseRoute()
    {
        return new ReverseRoute($this->routes);
    }
}