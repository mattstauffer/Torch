<?php

use Illuminate\Routing\Router;

/** @var $router Router */

$router->get('/', function () {
    return 'hello world!';
});

$router->get('bye', function () {
    return 'goodbye world!';
});

$router->group(['namespace' => 'Torch\Routing\Controllers', 'prefix' => 'users'], function (Router $router) {
    $router->get('/', ['name' => 'users.index', 'uses' => 'UsersController@index']);
    $router->post('/', ['name' => 'users.store', 'uses' => 'UsersController@store']);
});

// Example routes using middleware to enable basic session checks.
$router->group(['middleware' => ['web'], 'namespace' => 'Torch\Routing\Controllers'], function (Router $router) {
    $router->get('guest', function () {
        return 'Hello, guest!';
    })->middleware('guest');

    $router->get('login', 'DashboardController@login')->middleware('guest');
    $router->get('logout', 'DashboardController@logout')->middleware('auth');

    $router->get('dashboard', 'DashboardController@dashboard')->middleware('auth');
});

// catch-all route
$router->any('{any}', function () {
    return 'four oh four';
})->where('any', '(.*)');
