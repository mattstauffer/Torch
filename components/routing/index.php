<?php

/**
 * Illuminate/Routing
 *
 * @source https://github.com/illuminate/routing
 * @author https://github.com/jwalton512
 */

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

// Load the routes
require_once 'routes.php';

// Create a request from server variables
$request = Request::capture();

// Dispatch the request through the router
$response = $router->dispatch($request);

// Send the response back to the browser
$response->send();
