<?php

use Illuminate\Encryption\Encrypter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

require_once 'vendor/autoload.php';

/**
 * Illuminate/encryption
 *
 * Requires: symfony/security-core
 *
 * @source https://github.com/illuminate/encryption
 */

 // Instantiate App
 $app = AppFactory::create();

 // Middleware
 $app->add(new WhoopsMiddleware(['enable' => true]));

/*
 * This key is used by the Illuminate encrypter service and should be set
 * to a random, 16-character string, otherwise these encrypted strings
 * will not be safe. Please do this before deploying an application!
 */
$key = '1hs8heis)2(-*3d.';

$app->get('/', function (Request $request, Response $response) use ($key) {
    $encrypter = new Encrypter($key);

    // Encrypt Hello World string
    $encryptedHelloWorld = $encrypter->encrypt('Hello World');
    $response->getBody()->write("Here is the encrypted string: <hr>" . $encryptedHelloWorld . "<br><br><br>");

    // Decrypt encrypted string
    $decryptedHelloWorld = $encrypter->decrypt($encryptedHelloWorld);
    $response->getBody()->write("Here is the decrypted string: <hr>" . $decryptedHelloWorld . "<br><br>");

    return $response;
});

$app->run();
