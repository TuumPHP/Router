<?php
namespace Tuum\Router;

class ReverseRoute implements ReverseRouteInterface
{
    /**
     * list of route name and pattern, as
     * [ 'route name' => 'route pattern', ... ]
     *
     * @var array
     */
    public $routes = [];

    /**
     * @param array $routes
     */
    public function __construct($routes)
    {
        foreach($routes as $pattern => $handle) {
            if($handle instanceof Handler && isset($handle->data['name'])) {
                $name = $handle->data['name'];
            }
            elseif( is_array($handle) && isset($handle['name'])) {
                $name = $handle['name'];
            } else {
                continue;
            }
            $this->routes[$name] = $pattern;
        }
    }

    /**
     * @param string $name
     * @param array  $data
     * @return string
     */
    public function generate($name, $data=[])
    {
        if( !array_key_exists($name, $this->routes)) return '';

        $route = $this->routes[$name];

        // remove methods
        if (strpos($route, ':')!== false && substr($route, 0, 1) !== '/') {
            /** @noinspection PhpUnusedLocalVariableInspection */
            list($method, $route) = explode(':', $route, 2);
        }
        // replace parameters: {id} to $data['id']
        $route = preg_replace_callback(
            '/{([_0-9a-zA-Z]+)}|([*]{1})/',

            function($match) use($data) {
                
                if(isset($match[2])) { // for trailing '*'. 
                    return implode('/', $data);
                }
                if(isset($match[1])) { // other parameters
                    if(isset($data[$match[1]])) {
                        return $data[$match[1]];
                    }
                    return $match[1];
                }
                return ''; // else. not sure what is this.
            },
            $route);
        return $route;
    }
}