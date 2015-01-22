<?php
use Tuum\Router\Tuum\Matcher;

require_once(dirname(dirname(__DIR__)).'/src/Tuum/Matcher.php');

var_dump(Matcher::verify( '/path/to', '/path/to' ));
var_dump(Matcher::verify( '/path/',   '/path/to' ));
var_dump(Matcher::verify( '/path/{id}', '/path/to' ));
var_dump(Matcher::verify( 'path/{id}',  '/path/to' ));
var_dump(Matcher::verify( '/path/{id}', 'path/to' ));
var_dump(Matcher::verify( '/path/{id}/to', '/path/more/to' ));
var_dump(Matcher::verify( '/path/{id}/to', '/path/more/to/it' ));

var_dump(Matcher::verify( 'get:/path/{id}', '/path/1234', 'GET' ));
var_dump(Matcher::verify( 'get:/path/{id}', '/path/1234', 'PUT' ));
var_dump(Matcher::verify( 'put:/path/{id}', '/path/1234', 'PUT' ));
