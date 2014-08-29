<?php

use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;

require_once '../../vendor/autoload.php';

/**
 * Illuminate/translation
 *
 * Translate your applications with ease
 *
 * Requires: illuminate/filesystem
 *
 * @source https://github.com/illuminate/translation
 * @author Robin Malfait
 */

$app = new Slim\Slim();
$app->add(new Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function()
{
    /* Getting the file loader ready */
    $loader = new FileLoader(new Filesystem(), './lang');

    /* English translator (You can change the "en" to a getLocale function) */
    $transEnglish = new Translator($loader, "en");

    /* Dutch translator */
    $transDutch = new Translator($loader, "nl");

    echo "<h1>Translations</h1><pre>";

    echo "English: " . $transEnglish->get('talk.conclusion') . "\n";
    echo "Dutch:   " . $transDutch->get('talk.conclusion');
});

$app->run();