<?php

require_once 'vendor/autoload.php';

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Redis\Database;

/**
 * Illuminate/config
 *
 * @source https://github.com/illuminate/config
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

// Cache with file driver
$app->get('/', function () {
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

    // Store a value into cache for 500 minutes
    $cache->put('test', 'This is loaded from cache.', 500);

    // Echo out the value we just stored in cache
    echo $cache->get('test');
});

// Cache with redis driver
// NOTE: You will need to have redis running for this to work
$app->get('/redis', function () {
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

    $container['redis'] = new Database($container['config']['database.redis']);

    $cacheManager = new CacheManager($container);

    // Get the default cache driver (redis in this case)
    $cache = $cacheManager->store();

    // Or if you have multiple drivers configured, you can get the redis store like this:
    // $cache = $cacheManager->store('redis');

    $cache->put('test', 'This is loaded from cache.', 500);

    echo $cache->get('test');
});


// Cache with memcached driver
// NOTE: You will need to have memcached running for this to work
$app->get('/memcached', function () {
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

    // Get the default cache driver (redis in this case)
    $cache = $cacheManager->store();

    // Or if you have multiple drivers configured, you can get the memcached store like this:
    // $cache = $cacheManager->store('memcached');

    $cache->put('test', 'This is loaded from cache.', 500);

    echo $cache->get('test');
});

$app->run();
