<?php

use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Encryption\EncryptionServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Queue\Connectors\SyncConnector;
use Illuminate\Queue\QueueManager as Queue;
use Illuminate\Queue\QueueServiceProvider;


require_once 'vendor/autoload.php';

/**
 * Illuminate/queue
 *
 * Requires: illuminate/queue
 *           illuminate/events
 *           illuminate/config
 *           illuminate/encryption
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
$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);
date_default_timezone_set('UTC');

// BOOTSTRAP-------------------------------------------------------------------
$container = new Container;

$container['config'] = new Config(array_merge(
    require __DIR__ . '/config/app.php',
    require __DIR__ . '/config/queue.php'
));

(new EncryptionServiceProvider($container))->register();
(new EventServiceProvider($container))->register();
(new QueueServiceProvider($container))->register();

// END BOOTSTRAP---------------------------------------------------------------

$app->get('/', function () {
    echo '<a href="/sync">sync</a><br>' .
        '<a href="/beanstalkd/add">Beanstalkd - Add</a><br>' .
        '<a href="/beanstalkd/work/worker">Beanstalkd - Do work as a worker</a><br>' .
        '<a href="/beanstalkd/work/single">Beanstalkd - Do work one-off</a><br>';
});

$app->get('/sync', function () use ($container) {
    $queue = $container['queue'];

    $queue->push('doThing', ['string' => 'sync-' . date('r')]);

    echo 'Pushed an instance of doThing to sync driver.';
});

$app->get('/beanstalkd/add', function () use ($container) {
    $queue = $container['queue'];

    $queue->connection('beanstalkd')->push('doThing', ['string' => 'beanstalkd-' . date('r')]);

    echo 'Pushed an instance of doThing to beanstalkd.';
});

$app->get('/beanstalkd/work/worker', function() use ($container) {
    $queue = $container['queue'];

    $worker = new \Illuminate\Queue\Worker($queue);

    //  Params list for 'pop':
    //    * Name of the connection to use--you can define a unique connection name by passing a second parameter to addConnection above
    //    * Name of the queue to use--this is the value for the 'queue' key in addConnection
    //    * Number of seconds to delay a job if it fails
    //    * Maximum amount of memory to use
    //    * Time (in seconds) to sleep when no job is returned
    //    * Maximum number of times to retry the specific job item before discarding it
    while (true) {
        try {
            $worker->pop('beanstalkd', 'default', 3, 64, 30, 3);
        } catch (\Exception $e) {
            // Handle job exception
        }
    }
});

$app->get('/beanstalkd/work/single', function() use ($container) {
    $queue = $container['queue'];

    $worker = new \Illuminate\Queue\Worker($queue);

    //  Params list for 'pop':
    //    * Name of the connection to use--you can define a unique connection name by passing a second parameter to addConnection above
    //    * Name of the queue to use--this is the value for the 'queue' key in addConnection
    //    * Number of seconds to delay a job if it fails
    //    * Maximum amount of memory to use
    //    * Time (in seconds) to sleep when no job is returned
    //    * Maximum number of times to retry the specific job item before discarding it
    try {
        $worker->pop('beanstalkd', 'default', 3, 64, 30, 3);
        echo 'Popped<br>';
    } catch (\Exception $e) {
        // Handle job exception
        var_dump($e->getMessage());
    }
});

class doThing
{
    public function fire($job, $data)
    {
        $handle = fopen('proof.txt', 'w');
        fwrite($handle, "\n" . $data['string']);
        $job->delete();
    }
}

$app->run();
