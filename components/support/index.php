<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

require_once 'vendor/autoload.php';

$app = new \Slim\App();
// @todo Fix this
//$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function ($request, $response, $args) {

    return $response->write(file_get_contents('index.html'));

});


$app->get('/collection', function() {

    require('./subcomponents/collection.php');

});

$app->get('/fluent', function() {

    require('./subcomponents/fluent.php');

});


$app->get('/pluralizer', function() {

    require('./subcomponents/pluralizer.php');

});

$app->get('/str', function() {
    
    require('./subcomponents/str.php');
});

$app->get('/messageBag', function() {

    require('./subcomponents/messageBag.php');
});

$app->run();
