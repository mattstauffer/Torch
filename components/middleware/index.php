<?php

/**
 * Illuminate/Routing
 *
 * @source https://github.com/illuminate/routing
 * @contributor https://github.com/dead23angel
 * @contributor Matt Stauffer
 */
session_start();

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

// Using Illuminate/Events/Dispatcher here (not required); any implementation of
// Illuminate/Contracts/Event/Dispatcher is acceptable
$events = new Dispatcher(new Container);

// Create the router instance
$router = new Router($events);

// Array middlewares
$routeMiddleware = [
    'auth' => \App\Middleware\Authenticate::class,
    'guest' => \App\Middleware\RedirectIfAuthenticated::class,
];

// Load middlewares to router
foreach ($routeMiddleware as $key => $middleware) {
    $router->middleware($key, $middleware);
}

// Load the routes
require_once 'routes.php';

// Create a request from server variables
$request = Request::capture();

// Dispatch the request through the router
$response = $router->dispatch($request);

// Send the response back to the browser
$response->send();
