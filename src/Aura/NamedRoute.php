<?php
namespace Tuum\Router\Aura;

use Aura\Router\Map;
use Tuum\Router\RouteNamesInterface;

class NamedRoute implements RouteNamesInterface
{
    /**
     * @var Map
     */
    protected $route;

    /**
     * @param Map $route
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
    public function get($name, $args = [])
    {
        return $this->route->generate($name, $args);
    }
}