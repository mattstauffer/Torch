<?php

use Illuminate\Filesystem\Filesystem;

require_once 'vendor/autoload.php';
require_once '../../src/App.php';
require_once '../../src/ExceptionHandler.php';
date_default_timezone_set('UTC');

// initialize container/app
$container = App::getInstance();
$container->instance('app', $container);

// initialize exception handler
$container->bind(\Illuminate\Contracts\Debug\ExceptionHandler::class, ExceptionHandler::class);

// initialize event dispatcher
$container->singleton(\Illuminate\Contracts\Events\Dispatcher::class, function (\Illuminate\Container\Container $container) {
    return new \Illuminate\Events\Dispatcher($container);
});

// initialize file cache for schedule mutex
$container->instance('config', new \Illuminate\Config\Repository(require __DIR__ . '/config.php'));
$container->instance('files', new Filesystem);
$container->singleton(\Illuminate\Contracts\Cache\Factory::class, function (\Illuminate\Container\Container $container) {
    return new \Illuminate\Cache\CacheManager($container);
});

// initialize schedule
$container->singleton(\Illuminate\Console\Scheduling\Schedule::class, function (): \Illuminate\Console\Scheduling\Schedule {
    return tap(new \Illuminate\Console\Scheduling\Schedule('UTC'), function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        $schedule->useCache('file'); // cache store name to use for mutex
    });
});

// configure schedule with scheduled commands/callbacks like normal
$schedule = $container->make(\Illuminate\Console\Scheduling\Schedule::class);

$schedule->call(function () {
    echo 'hello from schedule - always'.PHP_EOL;
})->everyMinute()->name('torch:always');

$schedule->call(function () {
    echo 'hello from schedule - every five'.PHP_EOL;
})->everyFiveMinutes()->name('torch:every5');

$schedule->call(function () {
    echo 'hello from schedule - without overlapping'.PHP_EOL;
    sleep(90);
})->everyMinute()->name('torch:without-overlapping')->withoutOverlapping();

// initialize schedule:run command
$scheduler = new \Illuminate\Console\Scheduling\ScheduleRunCommand();
$scheduler->setLaravel($container);
$input = new \Symfony\Component\Console\Input\ArrayInput([]);
$output = new \Symfony\Component\Console\Output\BufferedOutput();
$scheduler->setInput($input);
$scheduler->setOutput(new \Illuminate\Console\OutputStyle($input, $output));

// run command without console application around
$scheduler->handle(
    $schedule,
    $container->make(\Illuminate\Contracts\Events\Dispatcher::class),
    $container->make(\Illuminate\Contracts\Debug\ExceptionHandler::class)
);

// echo buffered command output
echo PHP_EOL.$output->fetch();
