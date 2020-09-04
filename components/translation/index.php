<?php

use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;

require_once 'vendor/autoload.php';

/**
 * Illuminate/translation
 *
 * Requires: illuminate/filesystem
 *
 * @source https://github.com/illuminate/translation
 * @contributor Robin Malfait
 */

$app = new \Slim\App(['settings' => ['debug' => true]]);
$app->add(new Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {
    // Prepare the FileLoader
    $loader = new FileLoader(new Filesystem(), './lang');

    // Register the English translator
    $transEnglish = new Translator($loader, "en");

    // Register the Dutch translator
    $transDutch = new Translator($loader, "nl");

    echo "<h1>Translations</h1><pre>";

    echo "English: " . $transEnglish->get('talk.conclusion') . "\n";
    echo "Dutch:   " . $transDutch->get('talk.conclusion');
});

$app->run();
