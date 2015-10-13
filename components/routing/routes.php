<?php

$router->get('/', function () {
    return 'hello world!';
});

$router->get('/bye', function () {
    return 'goodbye world!';
});

// catch all route
$router->any('{any}', function () {
    return 'four oh four';
})->where('any', '(.*)');
