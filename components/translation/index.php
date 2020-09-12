<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

require_once 'vendor/autoload.php';

/**
 * Illuminate/translation
 *
 * Requires: illuminate/filesystem
 *
 * @source https://github.com/illuminate/translation
 * @contributor Robin Malfait
 */

 // Instantiate App
 $app = AppFactory::create();

 // Middleware
 $app->add(new WhoopsMiddleware(['enable' => true]));

$app->get('/', function (Request $request, Response $response) {
    // Prepare the FileLoader
    $loader = new FileLoader(new Filesystem(), './lang');

    // Register the English translator
    $transEnglish = new Translator($loader, "en");

    // Register the Dutch translator
    $transDutch = new Translator($loader, "nl");

    $response->getBody()->write("<h1>Translations</h1><pre>");

    $response->getBody()->write("English: " . $transEnglish->get('talk.conclusion') . "\n");
    $response->getBody()->write("Dutch:   " . $transDutch->get('talk.conclusion'));

    return $response;
});

$app->run();
