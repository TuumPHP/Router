<?php
namespace Tuum\Router\Symfony;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Tuum\Web\Http\Request;
use Tuum\Router\RouteNamesInterface;
use Tuum\Router\RouterInterface;

class Router implements RouterInterface
{
    /**
     * @var RouteCollection
     */
    protected $router;

    /**
     * @param RouteCollection $router
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * matches against $request.
     * returns matched result, or false if not matched.
     *
     * @param Request $request
     * @return mixed
     */
    public function match($request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->router, $context);
        return $matcher->match($request->getPathInfo());
    }

    /**
     * get router to set routes.
     * returns various router object.
     *
     * @return RouteCollection
     */
    public function router()
    {
        return $this->router;
    }

    /**
     * @param Request $request
     * @return \Tuum\Router\RouteNamesInterface
     */
    public function namedRoutes($request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $route = new UrlGenerator($this->router(), $context);
        return new NamedRoute($route);
    }
}