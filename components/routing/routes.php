<?php

use Illuminate\Routing\Router;

/** @var $router Router */

$router->get('/', function() {
	echo "index page";
});

$router->get('/hello/{name}', function($name) {
	echo "Hello ". $name;
});