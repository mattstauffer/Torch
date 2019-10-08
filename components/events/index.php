<?php

include 'vendor/autoload.php';

/*
 * Illuminate/events
 *
 * @source https://github.com/illuminate/events
 * @contributor https://github.com/angelov
 */

use App\Events\UserHasRegisteredEvent;
use App\Listeners\SendWelcomingEmail;

$dispatcher = new \Illuminate\Events\Dispatcher();

// Defining the listeners

$dispatcher->listen([UserHasRegisteredEvent::class], SendWelcomingEmail::class);

// Firing the event

$dispatcher->dispatch(new UserHasRegisteredEvent('example'));
