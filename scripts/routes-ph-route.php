<?php

use Tuum\Router\PhRouter\Router;
use Tuum\Stack\Dispatcher;
use Tuum\Stack\Routes;
use Tuum\Web\App;

/*
 * sample routes constructor script for locator.
 */

/** @var App $app */

/*
 * create a matcher. 
 */

$matcher = Router::forge();

/*
 * set up routes.
 */
$r  = $matcher->router();
$r->addRoute( 'get', 'todo', 'ToDoController' );

/*
 * construct a router
 */

$routes = Routes::forge($matcher, new Dispatcher($app));

// $routes->setRoot('example'); // may set a root
// $routes->setBeforeFilter('auth'); // before filter here.

return $routes;