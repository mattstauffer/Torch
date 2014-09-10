<?php

use Illuminate\Encryption\Encrypter;

require_once '../../vendor/autoload.php';

/**
 * Illuminate/encryption
 *
 * Requires: symfony/security-core
 *           illuminate/support (only used for ServiceProvider)
 *
 * @source https://github.com/illuminate/encryption
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

/*
| This key is used by the Illuminate encrypter service and should be set
| to a random, 32 character string, otherwise these encrypted strings
| will not be safe. Please do this before deploying an application!
*/
$key = '1hs8heis)2(-*3d.>d,;<adowpcjd:Df';

$app->get('/', function () use($key) {
	$encrypter = new Encrypter($key);

	// Encrypt Hello World string
	$encryptedHelloWorld = $encrypter->encrypt('Hello World');
    echo "Here is the encrypted string: <hr>" . $encryptedHelloWorld . "<br><br><br>";

    // Decrypt encrypted string
    $decryptedHelloWorld = $encrypter->decrypt($encryptedHelloWorld);
    echo "Here is the decrypted string: <hr>" . $decryptedHelloWorld . "<br><br>";
});
$app->run();
