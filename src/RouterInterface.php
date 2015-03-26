<?php
namespace Tuum\Router;

/**
 * Interface RouterInterface
 * 
 * an interface for matching a route against a request. 
 *
 */
interface RouterInterface
{
    /**
     * matches against $request.
     * returns matched result, or false if not matched.
     *
     * @param string $path
     * @param string $method
     * @return null|Route
     */
    public function match($path, $method);

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