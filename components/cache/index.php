<?php

require_once 'vendor/autoload.php';

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Redis\RedisManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

/**
 * Illuminate/config
 *
 * @source https://github.com/illuminate/config
 */

 // Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

// Cache with file driver
$app->get('/', function (Request $request, Response $response) {
    // Create a new Container object, needed by the cache manager.
    $container = new Container;

    // The CacheManager creates the cache "repository" based on config values
    // which are loaded from the config class in the container.
    // More about the config class can be found in the config component; for now we will use an array
    $container['config'] = [
        'cache.default' => 'file',
        'cache.stores.file' => [
            'driver' => 'file',
            'path' => __DIR__ . '/cache'
        ]
    ];

    // To use the file cache driver we need an instance of Illuminate's Filesystem, also stored in the container
    $container['files'] = new Filesystem;

    // Create the CacheManager
    $cacheManager = new CacheManager($container);

    // Get the default cache driver (file in this case)
    $cache = $cacheManager->store();

    // Or, if you have multiple drivers:
    // $cache = $cacheManager->store('file');

    if ($cache->has('test')) {
        // Echo out the value we just stored in cache
        $response->getBody()->write($cache->get('test'));

        return $response;
    }

    // Store a value into cache for 500 minutes
    $cache->put('test', 'This is loaded from cache.', 500);

    $response->getBody()->write('Storing into cache. Refresh to see the version pulled from cache.');

    return $response;
});

// Cache with redis driver
// NOTE: You will need to have redis running for this to work
$app->get('/redis', function (Request $request, Response $response) {
    $container = new Container;

    $container['config'] = [
        'cache.default' => 'redis',
        'cache.stores.redis' => [
            'driver' => 'redis',
            'connection' => 'default'
        ],
        'cache.prefix' => 'illuminate_non_laravel',
        'database.redis' => [
            'cluster' => false,
            'default' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'database' => 0,
            ],
        ]
    ];

    $container['redis'] = new RedisManager($container, 'predis', $container['config']['database.redis']);

    $cacheManager = new CacheManager($container);

    // Get the default cache driver (redis in this case)
    $cache = $cacheManager->store();

    // Or if you have multiple drivers configured, you can get the redis store like this:
    // $cache = $cacheManager->store('redis');

    if ($cache->has('test')) {
        // Echo out the value we just stored in cache
        $response->getBody()->write($cache->get('test'));

        return $response;
    }

    // Store a value into cache for 500 minutes
    $cache->put('test', 'This is loaded from Redis cache.', 500);

    $response->getBody()->write('Storing into cache. Refresh to see the version pulled from cache.');

    return $response;
});


// Cache with memcached driver
// NOTE: You will need to have memcached running for this to work
$app->get('/memcached', function (Request $request, Response $response) {
    if (! class_exists('Memcached')) {
        $response->getBody()->write('Sorry, but you have to have memcached enabled on your PHP install. More: <a href="https://serversforhackers.com/c/installing-php-7-with-memcached">https://serversforhackers.com/c/installing-php-7-with-memcached</a>');

        return $response;
    }

    $container = new Container;

    $container['config'] = [
        'cache.default' => 'memcached',
        'cache.stores.memcached' => [
            'driver' => 'memcached',
            'servers' => [
                [
                    'host' => getenv('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => getenv('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],
        'cache.prefix' => 'illuminate_non_laravel'
    ];

    $container['memcached.connector'] = new \Illuminate\Cache\MemcachedConnector();

    $cacheManager = new CacheManager($container);

    // Get the default cache driver (memcached in this case)
    $cache = $cacheManager->store();

    // Or if you have multiple drivers configured, you can get the memcached store like this:
    // $cache = $cacheManager->store('memcached');

    if ($cache->has('test')) {
        // Echo out the value we just stored in cache
        $response->getBody()->write($cache->get('test'));

        return $response;
    }

    // Store a value into cache for 500 minutes
    $cache->put('test', 'This is loaded from Memcached cache.', 500);

    $response->getBody()->write('Storing into cache. Refresh to see the version pulled from cache.');

    return $response;
});

$app->run();
