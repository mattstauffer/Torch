<?php

include 'vendor/autoload.php';

use App\Events\UserHasRegisteredEvent;
use App\Listeners\SendWelcomingEmail;

$dispatcher = new \Illuminate\Events\Dispatcher();

// Defining the listeners

$dispatcher->listen([UserHasRegisteredEvent::class], SendWelcomingEmail::class);

// Firing the event

$dispatcher->fire(new UserHasRegisteredEvent("example"));
