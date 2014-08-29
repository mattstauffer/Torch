<?php

/*
|--------------------------------------------------------------------------
| Use me right?
|--------------------------------------------------------------------------
*/

use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Slim\Slim;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;


/*
|--------------------------------------------------------------------------
| Composer: "illuminate/translation": "4.2.8"
|--------------------------------------------------------------------------
*/

require_once '../../vendor/autoload.php';


/*
|--------------------------------------------------------------------------
| Instantiate it
|--------------------------------------------------------------------------
*/

$app = new Slim();
$app->add(new WhoopsMiddleware);


/*
|--------------------------------------------------------------------------
| Make things work!
|--------------------------------------------------------------------------
*/

$app->get('/', function()
{
    /* Getting the file loader ready */
    $loader = new FileLoader(new Filesystem(), './lang');

    /* English translator (You can change the "en" to a getLocale function) */
    $transEnglish = new Translator($loader, "en");

    /* Dutch translator */
    $transDutch = new Translator($loader, "nl");

    echo "<h1>Translations right?!</h1><pre>";

    echo "English: " . $transEnglish->get('talk.conclusion') . "\n";
    echo "Dutch:   " . $transDutch->get('talk.conclusion');
});


/*
|--------------------------------------------------------------------------
| Boot it
|--------------------------------------------------------------------------
*/

$app->run();