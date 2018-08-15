<?php

/**
 * Illuminate/Routing
 *
 * @source https://github.com/illuminate/routing
 * @contributor https://github.com/dead23angel
 * @contributor Matt Stauffer
 */

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Router;

// Create new IoC Container instance
$container = new Container;

// Using Illuminate/Events/Dispatcher here (not required); any implementation of
// Illuminate/Contracts/Event/Dispatcher is acceptable
$events = new Dispatcher($container);

// Create the router instance
$router = new Router($events);

// Global middlewares
$globalMiddleware = [
    \App\Middleware\StartSession::class,
];

// Array middlewares
$routeMiddleware = [
    'auth' => \App\Middleware\Authenticate::class,
    'guest' => \App\Middleware\RedirectIfAuthenticated::class,
];

// Load middlewares to router
foreach ($routeMiddleware as $key => $middleware) {
    $router->aliasMiddleware($key, $middleware);
}

// Load the routes
require_once 'routes.php';

// Create a request from server variables
$request = Request::capture();

// Dispatching the request:
// When it comes to dispatching the request, you have two options:
// a) you either send the request directly through the router
// or b) you pass the request object through a stack of (global) middlewares
// then dispatch it.

// a. Dispatch the request through the router
// $response = $router->dispatch($request);

// b. Pass the request through the global middlewares pipeline then dispatch it through the router
$response = (new Pipeline($container))
    ->send($request)
    ->through($globalMiddleware)
    ->then(function ($request) use ($router) {
        return $router->dispatch($request);
    });

// Send the response back to the browser
$response->send();
