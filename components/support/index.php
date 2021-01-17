<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;

require_once 'vendor/autoload.php';

/**
 * Illuminate/support
 *
 * Provides array helpers, Collection, Fluent, Pluralizer, Str, MessageBag, and more
 *
 * Requires: illuminate/support
 *
 * @source https://github.com/illuminate/support
 */

 // Instantiate App
$app = AppFactory::create();

// Middleware
$app->add(new WhoopsMiddleware(['enable' => true]));

$app->get('/', function (Request $request, Response $response) {
    // MessageBag init
    $messageBag = new MessageBag;

    $response->getBody()->write('<h2>Array dot notation with Arr::get</h2>');
    $response->getBody()->write('<pre>');
    // Array dot notation (and other helpers)
    $person = [
        'name' => [
            'first' => 'Jill',
            'last' => 'Schmoe'
        ]
    ];
    $response->getBody()->write('name.first is ' . Arr::get($person, 'name.first') . "\n");

    $messageBag->add('notice', 'Array dot notation displayed.');

    $response->getBody()->write('</pre><hr>');


    // Collection
    $response->getBody()->write('<h2>Collection</h2>');
    $response->getBody()->write('<pre>');
    $people = new Collection(['Declan', 'Abner', 'Mitzi']);

    $people->map(function ($person) {
        return "<i>$person</i>";
    })->each(function ($person) use ($response) {
        $response->getBody()->write("Collection person: $person\n");
    });

    $messageBag->add('notice', 'Collection displayed.');

    $response->getBody()->write('</pre><hr>');

    // More at http://laravel.com/docs/5.1/collections


    // Fluent
    $response->getBody()->write('<h2>Fluent</h2>');
    $response->getBody()->write('<pre>');
    $personRecord = [
        'first_name' => 'Mohammad',
        'last_name' => 'Gufran'
    ];
    $record = new Fluent($personRecord);

    $record->address('hometown, street, house');

    $response->getBody()->write($record->first_name . "\n");
    $response->getBody()->write($record->address . "\n");

    $messageBag->add('notice', 'Fluent displayed.');

    $response->getBody()->write('</pre><hr>');


    // Pluralizer
    $response->getBody()->write('<h2>Pluralizer</h2>');
    $response->getBody()->write('<pre>');

    $item = 'goose';
    $response->getBody()->write("One $item, two " . Pluralizer::plural($item) . "\n");
    $item = 'moose';
    $response->getBody()->write("One $item, two " . Pluralizer::plural($item) . "\n");

    $response->getBody()->write('</pre><hr>');

    // Str
    $response->getBody()->write('<h2>Str</h2>');
    $response->getBody()->write('<pre>');

    if (Str::contains('This is my fourteenth visit', 'first')) {
        $response->getBody()->write('Howdy!');
    } else {
        $response->getBody()->write('Nice to see you again.');
    }

    $response->getBody()->write('</pre><hr>');

    $response->getBody()->write('<h2>MessageBag</h2>');
    $response->getBody()->write('<pre>');

    $response->getBody()->write("MessageBag ({$messageBag->count()})\n");
    foreach ($messageBag->all() as $message) {
        $response->getBody()->write(" - $message\n");
    }

    return $response;
});

$app->run();
