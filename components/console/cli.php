<?php

require 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Console\Application;

$container = new Container;
$events = new Dispatcher($container);

$cli = new Application($container,$events,'Version 1');
$cli->setName('My Console App Name');

//resolve a command
$cli->resolve('HelloWorld');

$cli->run();
