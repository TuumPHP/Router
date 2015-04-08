Router
======

Yet-another small router component for PHP. 

### MIT Licence

### Why another Routing component?

I was looking for a routing component that works as one-liner and have features like;

*   match for method and route pattern represented by a string,
*   match to parameterized token as well as beginning of path, and 
*   have a very small code base,

and I could not find one. It is possible to use just a regular expressions for the above purpose, but I found it too difficult to write a correct reg-ex everytime. 


Matcher
-------

### Basic usage

```php
use Tuum\Routing\Matcher;
$matched = Matcher::verify( $pattern, $path, $method);
```

If the $path matches with $pattern, the method returns 
array of matched information. Otherwise it returns 
an empty array. 

### Examples

matching to method ```get```, with integer parameter ```id```. 

```php
$matched = Matcher::verify('get:/path/{id:i}', '/path/1001', 'get');
if ($matched) {
    $id = $matched['id'];
}
```

matching to any method, starting with ```/path/to```.

```php
$matched = Matcher::verify('/path/to/*', '/path/to/more/route', 'ignored');
if ($matched) {
    echo $matched['matched']; // '/path/to/'
    echo $matched['trailing']; // 'more/route'
}
```

The Matcher class has no dependencies, with less than 100 lines of code including comments. 


### Closure style

Available as a static method as well as a closure style.

```php
$matcher = new Matcher;
$matched = $matcher('/my/resource/*', '/my/resource/1001', 'get');
```


### Returned value

The ```Matcher::verify``` method returns an array of matched parameters, 
or empty array if failed to match. 

The returned values are preg-match result, and some more. 
Please do not use strings as parameters. 

*   method: matched method. this value maybe empty if no method is specified. 
*   matched: matched base path if ```{*}``` is used. 
*   trailing: matched remaining path if ```{*}``` is used.


Available Route Patterns
------------------------

the route patterns. 

```
pattern :=  ([method]:)[route](*)
```


### route

Generally speaking, start route pattern with slash ('/'). 
In case the route contains a colon (':') but do not want to 
specify method, make sure the route start with slash. 


### parameter

syntax is ```{parameter_name(:type)}```. 

Parameter name must be alphabet and underscore only ([_0-9a-zA-Z]+), and 
matches only to alphabets, hyphen, and underscore ([-_0-9a-zA-Z]+).

Currently, only type of 'i' for integer is supported, i.e. ```/resource/{id:i}```. 

### method

Method name must be consisted of only alphabets. (method name cannot contain colon.)

To ignore the method in the match, use asterisk as the method value, like;

```php
Matcher::verify( 'get:/path/{id}', '/path/1234', '*' );
```

### trailing route (*)

To match any remaining route, use *, 

*   for any path: ```/path/to/*```, which just matches with any route. 
*   for matching trailing route: ```/path/to/{*}```, which 
	returns the matched route as ```matched```, and remaining route as ```trailing```, if matched. 


Router Class
--------------

Anything below are implemented to make this looks like a package.

```php
$router = new Router([
    'get:/path/' => 'index',
    'get:/path/{id}' => 'get',
]);
$router->addRoute('put:/path/{id}', 'put');
$matched = $router->match('/path/123');
if($matched) {
    $method = $matched[0]; // the handler
    $params = $matched[1]; // matched parameter
    $id = $params['id'];
}
```

Router class takes an array of patterns and its ```handler```, and matches against a path. A ```addRoute``` method adds a pattern and ```handler``` one by one. 

If matched, the router returns an array of the whatever the handler and matched parameter. 

Handler can be anything. It just returns whatever it is set. 


RouteCollection and Handler Class
------------------------------------

use RouteCollection and Handler objects for creating patterns with ease. 

```php
$router = new Router();
$routes = $router->getRouting();
$routes->any('/', 'top')
	->name('top');
$routes->get('/welcom', function(){ echo 'welcome';})
	->before('UserNameFilter');

$matched = $router->match('/welcom', 'get');
echo get_class($matched[0]); // Tuum\Routing\Handler
```

> Currently, the $router returns matched result as an array like any other. But it maybe easier to just return Handle object (if handle is the Handle object)... 

### Route Class

The ```Router::match``` method will return ```Route``` class if RouteCollection is used (i.e. Handler class is the $handler). 


### Handler and Route API list

The ```$routes``` (RouteCollector) uses ```Handler``` object to setup route information. 

Handler has following methods. 

```php
$routes->{method}($routePattern, $handler)
	->name($route_name)
	->before($filter_name)
	->after($may_not_work)
	->params($default_parameter);
```

Route class has following methods for reading matched information.

```php
echo $route->handle();
echo $route->name();
echo $route->before();
echo $route->after();
echo $route->params();
echo $route->path();
echo $route->method();
echo $route->trailing();
echo $route->matched();
```


### Grouping Routes

Use ```group``` method to assigning same properties and/or matching pattern to a group of routes, as follows. 

```php
$routes->group([
		'pattern' => '/admin/',
		'handler' => 'Admin\Controller\',
		'before' => 'AdminAuth',
	], 
	function($routes) {
		/** @var RouteCollector $routes */
		$routes->get('/', 'MainController');
	});
$matched = $route->match('/admin/', 'get');
```

ReverseRoute
----------------

To-be-altered. 

usage:

```php
$router = new Route();
$routes = $router->getRouting();
$routes->get('/sample/{id}', 'sample' )->name('sample');

$reverse = $router->getReverseRoute();
$route = $reverse->generate('sample',['id'=>'123']);
```
