<?php

require_once '../../vendor/autoload.php';

/**
 * Illuminate/routing
 *
 * Basic non-Slim, non-Laravel Illuminate Routing;
 * not in Slim because Slim has its own routing component
 *
 * Requires: illuminate/routing
 *           illuminate/events
 *
 * @todo Abstract out the creation of the $app/iOC so that we have a commonly-
 *       agreed-upon way to do it across all of the IlluminateNonLaravel
 *       examples.
 *
 * @source https://github.com/illuminate/routing
 * @author Mohammad Gufran & Matt Stauffer
 * @see http://www.gufran.me/post/laravel-components
 */

// Bootstrap App (Application Instance/IoC Container)
require_once 'bootstrap.php';

// Load routes file
require_once $basePath . 'routes.php';

// Get our request and generate our response
$request = Illuminate\Http\Request::createFromGlobals();
$response = $app['router']->dispatch($request);
$response->send();
