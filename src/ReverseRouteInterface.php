<?php
namespace Tuum\Router;

/**
 * Interface RouteNamesInterface
 * 
 * an interface for obtaining url from route names. 
 *
 * @package Tuum\Web\NamedRoutesInterface
 */
interface ReverseRouteInterface
{
    /**
     * @param string $name
     * @param array  $args
     * @return string
     */
    public function generate($name, $args=[]);
}