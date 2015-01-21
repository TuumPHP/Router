<?php
namespace Tuum\Router\PhRouter;

use Phroute\RouteCollector;
use Tuum\Router\ReverseRouteInterface;

class NamedRoute implements ReverseRouteInterface
{
    /**
     * @var RouteCollector
     */
    protected $route;

    /**
     * @param RouteCollector $route
     */
    public function __construct($route)
    {
        $this->route = $route;
    }
    
    /**
     * @param string $name
     * @param array  $args
     * @return string
     */
    public function generate($name, $args=[])
    {
        return $this->route->route($name, $args);
    }
}