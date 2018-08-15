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
    $router->get('/', ['as' => 'users.index', 'uses' => 'UsersController@index']);
    $router->post('/', ['as' => 'users.store', 'uses' => 'UsersController@store']);
});

// Naming routes using `as`:
$router->get('/articles', ['as' => 'articles.index', 'uses' => function () use ($urlGenerator) {
    return 'The url to the route named "articles.index" is: ' . $urlGenerator->route('articles.index');
}]);

// Naming routes using `name` method:
// Note: You are responsible for refreshing name & action lookups if you named your
// routes using `name` method otherwise an exception of "route not defined" will be thrown.
$router->get('/articles/create', function () use ($urlGenerator) {
    return 'The url to the route named "articles.create" is: ' . $urlGenerator->route('articles.create');
})->name('articles.create');

$router->getRoutes()->refreshNameLookups();
$router->getRoutes()->refreshActionLookups();

// catch-all route
$router->any('{any}', function () {
    return 'four oh four';
})->where('any', '(.*)');
