<?php
namespace Tuum\Router;

/**
 * Interface RouteNamesInterface
 * 
 * an interface for obtaining url from route names. 
 *
 * @package Tuum\Web\NamedRoutesInterface
 */
interface RouteNamesInterface
{
    /**
     * @param string $name
     * @param array  $args
     * @return string
     */
    public function get($name, $args=[]);
}