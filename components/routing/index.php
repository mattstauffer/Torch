<?php

use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

include 'vendor/autoload.php';

$router = new Router(new Dispatcher());

$router->get('/', function() {
	echo "index page";
});

$router->get('/hello/{name}', function($name) {
	echo "Hello ". $name;
});

$request = Request::createFromGlobals();

$response = $router->dispatch($request);

$response->send();