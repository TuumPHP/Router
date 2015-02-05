<?php
namespace Tuum\Router;

use Psr\Http\Message\RequestInterface;

/**
 * Interface RouterInterface
 * 
 * an interface for matching a route against a request. 
 *
 * @package Tuum\Web\ServiceInterface
 */
interface RouterInterface
{
    /**
     * matches against $request. 
     * returns matched result, or false if not matched. 
     * 
     * @param RequestInterface $request
     * @return Route|null
     */
    public function match($request);

    /**
     * get router to set routes. 
     * returns various router object. 
     * 
     * @return mixed
     */
    public function getRouting();

    /**
     * @return ReverseRouteInterface
     */
    public function getReverseRoute();
}