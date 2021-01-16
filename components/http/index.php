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
$response = $http->post('https://jsonplaceholder.typicode.com/posts', [
    'title' => 'foo',
    'body' => 'bar',
    'userId' => 1,
]);
$id = $response->json()['id'];

/** @var \Illuminate\Http\Client\Response $response */
$response = $http->get('https://jsonplaceholder.typicode.com/posts');
echo $response->body();
