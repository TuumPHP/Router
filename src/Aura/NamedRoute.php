<?php
namespace Tuum\Router\Aura;

use Aura\Router\Map;
use Tuum\Router\ReverseRouteInterface;

class NamedRoute implements ReverseRouteInterface
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
    public function generate($name, $args = [])
    {
        return $this->route->generate($name, $args);
    }
}