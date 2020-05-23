<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Router;

/** @var $router Router */

$router->name('home')->get('/', function () {
    return 'hello world!';
});

$router->get('bye', function () {
    return 'goodbye world!';
});

$router->group(['namespace' => 'App\Controllers', 'prefix' => 'users'], function (Router $router) {
    $router->get('/', ['name' => 'users.index', 'uses' => 'UsersController@index']);
    $router->post('/', ['name' => 'users.store', 'uses' => 'UsersController@store']);
});

// Redirect
$router->get('/menu', function () use ($router) {
    return new RedirectResponse($router->getRoutes()->getByName('home')->uri());
});

// catch-all route
$router->any('{any}', function () {
    return 'four oh four';
})->where('any', '(.*)');
