<?php
namespace Tuum\Router;

use Phroute\RouteCollector;
use Tuum\Web\NamedRoutesInterface\RouteNamesInterface;

class NamedRoute implements RouteNamesInterface
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
    public function get($name, $args=[])
    {
        return $this->route->route($name, $args);
    }
}