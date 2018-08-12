<?php

use Illuminate\Queue\Worker;
use Illuminate\Redis\RedisManager;
use Illuminate\Container\Container;
use Illuminate\Queue\WorkerOptions;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Queue\Capsule\Manager as Queue;
use Illuminate\Contracts\Debug\ExceptionHandler;

require_once 'vendor/autoload.php';

/**
 * Illuminate/queue
 *
 * Requires: illuminate/queue
 *           illuminate/events
 *           illuminate/redis *if using redis* (see docs for requirements for other drivers)
 *
 * Note: Laravel's queue driver is for pushing Closures and classes up to
 *       a queue, and then pulling them back down and operating on them.
 *       Unlike other queue drivers, there is no focus on pushing just strings,
 *       arrays, or objects for use by other programs or languages.
 *
 * @source https://github.com/illuminate/queue
 * @author https://github.com/mattstauffer
 */
$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);
date_default_timezone_set('UTC');

class App extends Container
{
    public function isDownForMaintenance()
    {
        return false;
    }
}

// BOOTSTRAP-------------------------------------------------------------------
$container = new App;

(new EventServiceProvider($container))->register();

$container->bind('redis', function () {
    return new RedisManager('predis', [
        'default' => [
            'host' => '127.0.0.1',
            'password' => null,
            'port' => 6379,
            'database' => 0,
        ],
    ]);
});

$container->bind('exception.handler', function () {
    return new class implements ExceptionHandler
    {
        public function report(Exception $e)
        {
            var_dump($e->getMessage());
        }

        public function render($request, Exception $e)
        {
            var_dump($e->getMessage());
        }

        public function renderForConsole($output, Exception $e)
        {
            var_dump($e->getMessage());
        }
    };
});

$queue = new Queue($container);

$queue->addConnection([
    'driver' => 'sync',
]);

$queue->addConnection([
    'driver' => 'redis',
    'connection' => 'default',
    'queue' => 'default',
], 'redis');

$container['queue'] = $queue->getQueueManager();

// END BOOTSTRAP---------------------------------------------------------------

$app->get('/', function () {
    echo '<a href="/sync">sync</a><br>' .
        '<a href="/redis/add">Redis - Add</a><br>' .
        '<a href="/redis/work/worker">Redis - Do work as a worker</a><br>' .
        '<a href="/redis/work/single">Redis - Do work one-off</a><br>';
});

$app->get('/sync', function () use ($container) {
    $queue = $container['queue'];

    $queue->push('DoThing', ['string' => 'sync-' . date('r')]);

    echo 'Pushed an instance of DoThing to sync driver.';
});

$app->get('/redis/add', function () use ($container) {
    $queue = $container['queue'];

    $queue->connection('redis')->push('DoThing', ['string' => 'redis-' . date('r')]);

    echo 'Pushed an instance of DoThing to redis.';
});

$app->get('/redis/work/worker', function () use ($container) {
    $queue = $container['queue'];
    $events = $container['events'];
    $handler = $container['exception.handler'];

    $worker = new Worker($queue, $events, $handler);
    $options = new WorkerOptions();

    $worker->daemon('redis', 'default', $options);
});

$app->get('/redis/work/single', function () use ($container) {
    $queue = $container['queue'];
    $events = $container['events'];
    $handler = $container['exception.handler'];

    $worker = new Worker($queue, $events, $handler);
    $options = new WorkerOptions();

    $worker->runNextJob('redis', 'default', $options);
    echo 'Ran job';
});

class DoThing
{
    public function fire($job, $data)
    {
        $handle = fopen('proof.txt', 'a');
        fwrite($handle, "\n" . $data['string']);
        $job->delete();
    }
}

$app->run();
