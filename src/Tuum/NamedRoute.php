<?php
namespace Tuum\Router\Tuum;

use Tuum\Router\ReverseRouteInterface;
use Tuum\Routing\ReverseRoute;

class NamedRoute implements ReverseRouteInterface
{
    /**
     * @var ReverseRoute
     */
    protected $reverseRoute;

    /**
     * @param $rev
     */
    public function __construct($rev)
    {
        $this->reverseRoute = $rev;
    }

    /**
     * @param string $name
     * @param array  $args
     * @return string
     */
    public function generate($name, $args = [])
    {
        return $this->reverseRoute->generate($name, $args);
    }
}