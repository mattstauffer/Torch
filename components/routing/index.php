<?php

use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

include 'vendor/autoload.php';

$router = new Router(new Dispatcher());

include 'routes.php';

$request = Request::createFromGlobals();

$response = $router->dispatch($request);

$response->send();