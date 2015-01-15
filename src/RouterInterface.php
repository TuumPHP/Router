<?php
namespace Tuum\Router;

use Tuum\Web\Http\Request;
use Tuum\Router\RouteNamesInterface;

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
     * @param Request $request
     * @return mixed
     */
    public function match($request);

    /**
     * get router to set routes. 
     * returns various router object. 
     * 
     * @return mixed
     */
    public function router();

    /**
     * @param Request $request
     * @return RouteNamesInterface
     */
    public function namedRoutes($request);
}