<?php

use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

include 'vendor/autoload.php';

$router = new Router(new Dispatcher());

$router->group(['namespace' => 'App\Controllers'], function(Router $router) {
	include 'routes.php';
});

$request = Request::createFromGlobals();

$response = $router->dispatch($request);

$response->send();