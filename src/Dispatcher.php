<?php
namespace Tuum\Router;

use Closure;
use Tuum\Web\Http\Request;
use Tuum\Web\Http\Response;
use Tuum\Web\App\AppHandleInterface;
use Tuum\Web\App;

class Dispatcher
{
    /**
     * @param Request $request
     * @param Route   $route
     * @return null|Response
     */
    public function __invoke($request, $route)
    {
        $class = $route->handle();

        // prepare object to dispatch.
        if (is_string($class) ) {
            if (method_exists($class, 'forge')) {
                $next = $class::forge();
            } else {
                $next = new $class;
            }
        } else {
            $next = $class;
        }
        // dispatch the next object.
        if ($next instanceof AppHandleInterface) {
            return $next->__invoke($request);
        }
        if ($next instanceof \Closure) {
            return $next($request);
        }
        throw new \InvalidArgumentException();
    }
}
