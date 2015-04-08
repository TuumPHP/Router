<?php
namespace Tuum\Router;

use Closure;

class RouteCollector
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var array
     */
    public $default = [];

    /**
     * @var string
     */
    public $default_pattern = '';

    /**
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->router  = $router;
    }

    /**
     * @param array    $default
     * @param callback $callback
     * @return $this
     */
    public function group($default, $callback)
    {
        $routes = new self($this->router);
        $routes->setDefault($default);
        $callback($routes);
        return $this;
    }

    /**
     * @param array $default
     * @return $this
     */
    protected function setDefault($default)
    {
        if(isset($default['pattern'])) {
            $this->default_pattern = $default['pattern'];
            unset($default['pattern']);
        }
        $this->default = $default;
        return $this;
    }

    /**
     * @param string         $pattern
     * @param string|Closure $handle
     * @return Handler
     */
    public function get($pattern, $handle)
    {
        return $this->addRoute('get', $pattern, $handle);
    }

    /**
     * @param string         $pattern
     * @param string|Closure $handle
     * @return Handler
     */
    public function put($pattern, $handle)
    {
        return $this->addRoute('put', $pattern, $handle);
    }

    /**
     * @param string         $pattern
     * @param string|Closure $handle
     * @return Handler
     */
    public function post($pattern, $handle)
    {
        return $this->addRoute('post', $pattern, $handle);
    }

    /**
     * @param string         $pattern
     * @param string|Closure $handle
     * @return Handler
     */
    public function delete($pattern, $handle)
    {
        return $this->addRoute('delete', $pattern, $handle);
    }

    /**
     * @param string         $pattern
     * @param string|Closure $handle
     * @return Handler
     */
    public function any($pattern, $handle)
    {
        return $this->addRoute(null, $pattern, $handle);
    }

    /**
     * @param string         $method
     * @param string         $pattern
     * @param string|Closure $handle
     * @return Handler
     */
    public function addRoute($method, $pattern, $handle)
    {
        $pattern = $this->default_pattern.$pattern;
        if ($method) {
            $pattern = "{$method}:{$pattern}";
        }
        $handler = new Handler($handle, $this->default);
        $this->router->addRoute($pattern, $handler);
        return $handler;
    }
}
