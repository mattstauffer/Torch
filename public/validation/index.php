<?php

/**
 * Illuminate/Validation
 *
 * The Laravel validation component provides a simple,
 * convenient interface for validating data.
 *
 * Requires: illuminate/container
 *           illuminate/database // If using database validation
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
    'templates.path' => './templates/'
]);

$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () use ($app)
{
    return $app->render('home.php');
});

$app->get('/no-database', function () use ($app)
{
    return $app->render('form.php', [
        'posted' => false,
        'errors' => null,
        'email' => '',
    ]);
});

$app->post('/no-database', function () use ($app)
{
    $loader = new FileLoader(new Filesystem, 'lang');
    $translator = new Translator($loader, 'en');
    $validation = new Factory($translator, new Container);

    $data = ['email' => $_POST['email']];
    $rules = ['email' => 'required|email'];
    $errors = null;

    $validator = $validation->make($data, $rules);

    if ($validator->fails()) {
        $errors = $validator->errors();
    }

    return $app->render('form.php', [
        'posted' => true,
        'errors' => $errors,
        'email' => $_POST['email'],
    ]);
});

// For a thorough example, we establish a database connection
// to drive the database presence verifier used by the validator.
// If you do not need to validate against values in the database,
// the database presence verifier and related code can be removed.
$app->get('/database', function () use ($app)
{
    return $app->render('form.php', [
        'posted' => false,
        'errors' => null,
        'email' => '',
    ]);
});

$app->post('/database', function () use ($app)
{
    $capsule = new Capsule;

    $capsule->addConnection([
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'illuminate_non_laravel',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]);

    $loader = new FileLoader(new Filesystem, 'lang');
    $translator = new Translator($loader, 'en');
    $presence = new DatabasePresenceVerifier($capsule->getDatabaseManager());
    $validation = new Factory($translator, new Container);

    $validation->setPresenceVerifier($presence);

    $data = ['email' => $_POST['email']];
    $rules = ['email' => 'required|email|unique:users'];
    $errors = null;

    $validator = $validation->make($data, $rules);

    if ($validator->fails()) {
        $errors = $validator->errors();
    }

    return $app->render('form.php', [
        'posted'   => true,
        'errors' => $errors,
        'email'    => $_POST['email'],
    ]);
});

$app->run();
