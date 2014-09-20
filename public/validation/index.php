<?php

/**
 * Illuminate/Validation
 *
 * The Laravel validation component provides a simple, 
 * convenient interface for validating data.
 * 
 * Requires: illuminate/container
 *           illuminate/database
 *           illuminate/filesystem
 *           illuminate/translation
 * 
 * @source https://github.com/illuminate/validation
 */

require_once '../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;

$app = new \Slim\Slim();

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function ()
{
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver'    => 'mysql',
        'host'      => getenv('DB_HOST'),
        'database'  => getenv('DB_NAME'),
        'username'  => getenv('DB_USER'),
        'password'  => getenv('DB_PASS'),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    $loader     = new FileLoader(new Filesystem, 'lang');
    $translator = new Translator($loader, 'en');
    $presence   = new DatabasePresenceVerifier($capsule->getDatabaseManager());
    $validation = new Factory($translator, new Container);

    $validation->setPresenceVerifier($presence);

    $rules = [
        'email' => 'email',
    ];

    $data  = [
        'email' => 'steve@apple.com',
    ];

    $validator = $validation->make($data, $rules);

    if ($validator->passes())
    {
        var_dump('All good.');
    }

    $data  = [
        'email' => 'bad@email',
    ];

    $validator = $validation->make($data, $rules);

    if ($validator->fails())
    {
        var_dump('No good.');

        var_dump($validator->errors()->toArray());    
    }

});

$app->run();

