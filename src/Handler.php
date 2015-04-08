<?php
namespace Tuum\Router;

use Closure;

/**
 * Class Handler
 *
 * @package Tuum\Routing
 *
 * @property string handle
 * @property string name
 * @property array  before
 * @property array  after
 * @property array  params
 */
class Handler
{
    /**
     * @var array
     */
    public $data = [];
    
    protected $keys = [
        'handle' => null, 
        'name' => null, 
        'before' => [], 
        'after' => [],
        'params' => []
    ];

    /**
     * @param string|Closure $handle
     * @param array          $default
     */
    public function __construct($handle, $default=[])
    {
        $this->data    = $default;
        $this->append('handle', $handle);
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    public function __get($key)
    {
        if(array_key_exists($key, $this->keys)) {
            return $this->g($key, $this->keys[$key]);
        }
        return null;
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed|null
     */
    protected function g($key, $default=null)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    /**
     * for reverse routing. 
     * 
     * @param string $name
     * @return $this
     */
    public function name($name)
    {
        return $this->append('name', $name);
    }

    /**
     * add before filter.
     *
     * @param mixed $filter
     * @return $this
     */
    public function before($filter)
    {
        return $this->setAsArray('before', $filter);
    }

    /**
     * add after filter.
     *
     * @param mixed $filter
     * @return $this
     */
    public function after($filter)
    {
        return $this->setAsArray('after', $filter);
    }

    /**
     * @param array $params
     * @return $this
     */
    public function params($params)
    {
        $this->data['params'] = $params;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    protected function append($name, $value)
    {
        if(!array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        } else {
            $this->data[$name] .= $value;
        }
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    protected function setAsArray($name, $value)
    {
        if (!array_key_exists($name, $this->data)) {
            $this->data[$name] = [];
        }
        $this->data[$name][] = $value;
        return $this;
    }
}
