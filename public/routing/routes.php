<?php

$app['router']->get('/', function() {
    return '<a href="world">Hello</a> world!';
});

$app['router']->get('/world', function() {
    return 'World hello!';
});