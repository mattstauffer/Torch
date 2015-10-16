<?php

use Illuminate\Routing\Router;

/** @var $router Router */

$router->get('/', function () {
    return 'hello world!';
});

$router->get('bye', function () {
    return 'goodbye world!';
});

$router->group(['namespace' => 'App\Controllers', 'prefix' => 'users'], function (Router $router) {
    $router->get('/', ['name' => 'users.index', 'uses' => 'UsersController@index']);
    $router->post('/', ['name' => 'users.store', 'uses' => 'UsersController@store']);
});

// catch-all route
$router->any('{any}', function () {
    return 'four oh four';
})->where('any', '(.*)');
