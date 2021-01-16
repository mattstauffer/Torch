<?php
require_once 'vendor/autoload.php';
date_default_timezone_set('UTC');

/**
 * Illuminate/http
 *
 * @source https://github.com/illuminate/http
 */

$http = new \Illuminate\Http\Client\Factory();

/** @var \Illuminate\Http\Client\Response $response */
$response = $http->get('https://jsonplaceholder.typicode.com/todos');

echo $response->body();
