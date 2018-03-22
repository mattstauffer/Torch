<?php

/**
 * Illuminate/Routing
 *
 * @source https://github.com/illuminate/routing
 * @contributor Muhammed Gufran
 * @contributor Matt Stauffer
 * @contributor https://github.com/jwalton512
 * @contributor https://github.com/dead23angel
 */

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;

// Create a service container
$container = new Container;

// Create a request from server variables, and bind it to the container; optional
$request = Request::capture();
$container->instance(Request::class, $request);

// Using Illuminate/Events/Dispatcher here (not required); any implementation of
// Illuminate/Contracts/Event/Dispatcher is acceptable
$events = new Dispatcher($container);

// Create the router instance
$router = new Router($events, $container);

// Optionally define individual and/or grouped middleware available for use by routes.
$singleMiddleware = [
    'guest' => \Torch\Routing\Middleware\RedirectIfAuthenticated::class,
    'auth' => \Torch\Routing\Middleware\Authenticate::class,
];

foreach ($singleMiddleware as $alias => $class) {
    $router->aliasMiddleware($alias, $class);
}

$middlewareGroups = [
    'web' => [
        \Torch\Routing\Middleware\StartSession::class,
    ],
];

foreach ($middlewareGroups as $alias => $group) {
    $router->middlewareGroup($alias, $group);
}

// Load the routes
require_once 'routes.php';

// Create the redirect instance
$redirect = new Redirector(new UrlGenerator($router->getRoutes(), $request));

// Redirect usage examples:
// return $redirect->home();
// return $redirect->back();
// return $redirect->to('/');

// Dispatch the request through the router
$response = $router->dispatch($request);

// Send the response back to the browser
$response->send();
