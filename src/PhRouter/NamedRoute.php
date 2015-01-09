<?php
namespace Tuum\Router\PhRouter;

use Phroute\RouteCollector;
use Tuum\Web\ServiceInterface\RouteNamesInterface;

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