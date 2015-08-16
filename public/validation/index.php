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

$app = new \Slim\Slim([
    'templates.path' => './'
]);

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function() use ($app)
{
    return $app->render('form.php', [
        'posted'   => false,
        'messages' => null,
        'email'    => '',
    ]);
});

$app->post('/', function() use ($app)
{
    // For a thorough example, we establish a database connection
    // to drive the database presence verifier used by the validator.
    // If you do not need to validate against values in the database,
    // the database presence verifier and related code can be removed.
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

    $data     = ['email' => $_POST['email']];
    $rules    = ['email' => 'required|email'];
    $messages = null;

    $validator = $validation->make($data, $rules);

    if ($validator->fails())
    {
        $messages = $validator->errors();
    }

    return $app->render('form.php', [
        'posted'   => true,
        'messages' => $messages,
        'email'    => $_POST['email'],
    ]);
});

$app->run();
