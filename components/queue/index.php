<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Events\Dispatcher;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Queue\Capsule\Manager as Queue;
use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use Illuminate\Redis\RedisManager;
use Predis\Client;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

require_once 'vendor/autoload.php';

/**
 * Illuminate/queue
 *
 * Requires: illuminate/queue
 *           illuminate/events
 *           illuminate/redis *if using redis* (see docs for requirements for other drivers)
 *           pda/pheanstalk *if using beanstalkd* (see docs for requirements for other drivers)
 *
 * Note: Laravel's queue driver is for pushing Closures and classes up to
 *       a queue, and then pulling them back down and operating on them.
 *       Unlike other queue drivers, there is no focus on pushing just strings,
 *       arrays, or objects for use by other programs or languages.
 *
 * @source https://github.com/illuminate/queue
 * @author https://github.com/mattstauffer
 */

// Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

date_default_timezone_set('UTC');

class App extends Container
{
    public function isDownForMaintenance()
    {
        return false;
    }
}

// BOOTSTRAP-------------------------------------------------------------------
$container = App::getInstance();

(new EventServiceProvider($container))->register();

$container->instance('Illuminate\Contracts\Events\Dispatcher', new Dispatcher($container));

$container->bind('redis', function () use ($container) {
    return new RedisManager($container, 'predis', [
        'default' => [
            'host' => '127.0.0.1',
            'password' => null,
            'port' => 6379,
            'database' => 0,
        ],
    ]);
});

$container->bind('exception.handler', function () {
    return new class implements ExceptionHandler {
        public function shouldReport(Throwable $e)
        {
            throw $e;
        }

        public function report(Throwable $e)
        {
            throw $e;
        }

        public function render($request, Throwable $e)
        {
            throw $e;
        }

        public function renderForConsole($output, Throwable $e)
        {
            throw $e;
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

$queue->addConnection([
    'driver' => 'beanstalkd',
    'host' => 'localhost',
    'queue' => 'default',
], 'beanstalkd');

$container['queue'] = $queue->getQueueManager();

// END BOOTSTRAP---------------------------------------------------------------

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write(implode('<br>', [
        '<a href="/sync">sync</a>',
        '<a href="/redis/add">Redis - Add</a>',
        '<a href="/redis/work/worker">Redis - Do work as a worker</a>',
        '<a href="/redis/work/single">Redis - Do work one-off</a>',
        '<a href="/beanstalkd/add">Beanstalkd - Add</a>',
        '<a href="/beanstalkd/work/worker">Beanstalkd - Do work as a worker</a>',
        '<a href="/beanstalkd/work/single">Beanstalkd - Do work one-off</a>'
    ]));

    return $response;
});

$app->get('/sync', function (Request $request, Response $response) use ($container) {
    $queue = $container['queue'];

    $queue->push('DoThing', ['string' => 'sync-' . date('r')]);

    $response->getBody()->write('Pushed an instance of DoThing to sync driver.');

    return $response;
});

$app->get('/redis/add', function (Request $request, Response $response) use ($container) {
    $queue = $container['queue'];

    $queue->connection('redis')->push('DoThing', ['string' => 'redis-' . date('r')]);

    $response->getBody()->write('Pushed an instance of DoThing to redis.');

    return $response;
});

$app->get('/redis/work/worker', function (Request $request, Response $response) use ($container) {
    $queue = $container['queue'];
    $events = $container['events'];
    $handler = $container['exception.handler'];
    $isDownForMaintenance = function () use ($container) {
        return $container->isDownForMaintenance();
    };

    $worker = new Worker($queue, $events, $handler, $isDownForMaintenance);
    $options = new WorkerOptions();

    $worker->daemon('redis', 'default', $options);

    return $response;
});

$app->get('/redis/work/single', function (Request $request, Response $response) use ($container) {
    $queue = $container['queue'];
    $events = $container['events'];
    $handler = $container['exception.handler'];
    $isDownForMaintenance = function () use ($container) {
        return $container->isDownForMaintenance();
    };

    $worker = new Worker($queue, $events, $handler, $isDownForMaintenance);
    $options = new WorkerOptions();

    $worker->runNextJob('redis', 'default', $options);

    $response->getBody()->write('Ran job');

    return $response;
});

$app->get('/beanstalkd/add', function (Request $request, Response $response) use ($container) {
    $queue = $container['queue'];

    $queue->connection('beanstalkd')->push('DoThing', ['string' => 'beanstalkd-' . date('r')]);

    $response->getBody()->write('Pushed an instance of DoThing to beanstalkd.');

    return $response;
});

$app->get('/beanstalkd/work/worker', function (Request $request, Response $response) use ($container) {
    $queue = $container['queue'];
    $events = $container['events'];
    $handler = $container['exception.handler'];
    $isDownForMaintenance = function () use ($container) {
        return $container->isDownForMaintenance();
    };

    $worker = new Worker($queue, $events, $handler, $isDownForMaintenance);
    $options = new WorkerOptions();

    $worker->daemon('beanstalkd', 'default', $options);

    return $response;
});

$app->get('/beanstalkd/work/single', function (Request $request, Response $response) use ($container) {
    $queue = $container['queue'];
    $events = $container['events'];
    $handler = $container['exception.handler'];
    $isDownForMaintenance = function () use ($container) {
        return $container->isDownForMaintenance();
    };

    $worker = new Worker($queue, $events, $handler, $isDownForMaintenance);
    $options = new WorkerOptions();

    $worker->runNextJob('beanstalkd', 'default', $options);
    $response->getBody()->write('Ran job');

    return $response;
});

class DoThing
{
    public function fire($job, $data)
    {
        $handle = fopen('proof.txt', 'a');
        fwrite($handle, PHP_EOL . $data['string']);
        $job->delete();
    }
}

$app->run();
