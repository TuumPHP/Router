<?php
namespace Tuum\Router;

/**
 * Class Handler
 *
 * @package Tuum\Router
 */
class Route
{
    public $data = [];

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return null|string
     */
    public function handle()
    {
        return $this->__get('handle');
    }

    /**
     * @return null|string
     */
    public function params()
    {
        return $this->__get('params');
    }

    /**
     * @return null|string
     */
    public function name()
    {
        return $this->__get('name');
    }

    /**
     * @return null|array
     */
    public function before()
    {
        return $this->__get('before');
    }

    /**
     * @return null|array
     */
    public function after()
    {
        return $this->__get('after');
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function __get($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }
}