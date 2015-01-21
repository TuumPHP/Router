<?php
namespace Tuum\Router\Symfony;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Tuum\Router\ReverseRouteInterface;

class NamedRoute implements ReverseRouteInterface
{
    /**
     * @var UrlGenerator
     */
    protected $route;

    /**
     * @param UrlGenerator $route
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
        return $this->route->generate($name, $args);
    }
}