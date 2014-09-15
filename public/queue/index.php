<?php

use Illuminate\Queue\Capsule\Manager as Queue;

require_once '../../vendor/autoload.php';

/**
 * Illuminate/queue
 *
 * Requires: illuminate/queue
 *           illuminate/http/request (if using iron.io, others? unsure?)
 *           iron-io/iron_mq (if using iron.io)
 *
 * Note: Laravel's queue driver is for pushing Closures and classes up to
 *       a queue, and then pulling them back down and operating on them.
 *       Unlike other queue drivers, there is no focus on pushing just strings,
 *       arrays, or objects for use by other programs or languages.
 *
 * @source https://github.com/illuminate/queue
 * @see http://safeerahmed.uk/illuminate-queues-everywhere-laravel-4-queues-component
 */
$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);
date_default_timezone_set('UTC');

// BOOTSTRAP-------------------------------------------------------------------
$queue = new Queue;

// Make this Capsule instance available globally via static methods... (optional)
$queue->setAsGlobal();

$queue->getContainer()->bind('encrypter', function() {
	return new Illuminate\Encryption\Encrypter('foobar');
});
// END BOOTSTRAP---------------------------------------------------------------

$app->get('/sync', function () use ($queue)
{
	$queue->addConnection([
		'driver' => 'sync'
	]);

	Queue::push('doThing', array('string' => 'sync-' . date('r')));

	echo 'Pushed an instance of doThing to sync driver.';
});

$app->get('/ironio/add', function () use ($queue)
{
	$queue->getContainer()->bind('request', function() {
		return new Illuminate\Http\Request();
	});
	$queue->getContainer()->bind('IronMQ', function() {
		return new IronMQ;
	});

	$queue->addConnection([
		'driver'  => 'iron',
		'project' => 'your-project-id',
		'token'   => 'your-token',
		'queue'   => 'illuminate-test',
		'encrypt' => true,
	]);

	Queue::push('doThing', array('string' => 'iron-' . date('r')));

	echo 'Pushed an instance of doThing to iron.io.';
});

$app->get('/ironio/work/worker', function() use ($queue)
{
	$queue->getContainer()->bind('request', function() {
		return new Illuminate\Http\Request();
	});
	$queue->getContainer()->bind('IronMQ', function() {
		return new IronMQ;
	});

	$queue->addConnection([
		'driver'  => 'iron',
		'project' => 'your-project-id',
		'token'   => 'your-token',
		'queue'   => 'illuminate-test',
		'encrypt' => true,
	]);

	$worker = new \Illuminate\Queue\Worker($queue->getQueueManager());

	//	Params list for 'pop':
	//	  * Name of the connection to use--you can define a unique connection name by passing a second parameter to addConnection above
	//	  * Name of the queue to use--this is the value for the 'queue' key in addConnection
	//	  * Number of seconds to delay a job if it fails
	//	  * Maximum amount of memory to use
	//	  * Time (in seconds) to sleep when no job is returned
	//	  * Maximum number of times to retry the specific job item before discarding it
	while (true) {
		try {
			$worker->pop('default', 'illuminate-test', 3, 64, 30, 3);
		} catch (\Exception $e) {
			// Handle job exception
		}
	}
});

$app->get('/ironio/work/single', function() use ($queue)
{
	$queue->getContainer()->bind('request', function() {
		return new Illuminate\Http\Request();
	});
	$queue->getContainer()->bind('IronMQ', function() {
		return new IronMQ;
	});

	$queue->addConnection([
		'driver'  => 'iron',
		'project' => 'your-project-id',
		'token'   => 'your-token',
		'queue'   => 'illuminate-test',
		'encrypt' => true,
	]);

	$worker = new \Illuminate\Queue\Worker($queue->getQueueManager());

	//	Params list for 'pop':
	//	  * Name of the connection to use--you can define a unique connection name by passing a second parameter to addConnection above
	//	  * Name of the queue to use--this is the value for the 'queue' key in addConnection
	//	  * Number of seconds to delay a job if it fails
	//	  * Maximum amount of memory to use
	//	  * Time (in seconds) to sleep when no job is returned
	//	  * Maximum number of times to retry the specific job item before discarding it
	try {
		$worker->pop('default', 'illuminate-test', 3, 64, 30, 3);
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
		fwrite($handle, $data['string']);

		$job->delete();
	}
}

$app->run();
