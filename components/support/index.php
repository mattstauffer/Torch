<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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

$app = new \Slim\App();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {
    // MessageBag init
    $messageBag = new MessageBag;

    echo '<h2>Array dot notation with Arr::get</h2>';
    echo '<pre>';
    // Array dot notation (and other helpers)
    $person = [
        'name' => [
            'first' => 'Jill',
            'last' => 'Schmoe'
        ]
    ];
    echo 'name.first is ' . Arr::get($person, 'name.first') . "\n";

    $messageBag->add('notice', 'Array dot notation displayed.');

    echo '</pre><hr>';


    // Collection
    echo '<h2>Collection</h2>';
    echo '<pre>';
    $people = new Collection(['Declan', 'Abner', 'Mitzi']);

    $people->map(function ($person) {
        return "<i>$person</i>";
    })->each(function ($person) {
        echo "Collection person: $person\n";
    });

    $messageBag->add('notice', 'Collection displayed.');

    echo '</pre><hr>';

    // More at http://laravel.com/docs/5.1/collections


    // Fluent
    echo '<h2>Fluent</h2>';
    echo '<pre>';
    $personRecord = [
        'first_name' => 'Mohammad',
        'last_name' => 'Gufran'
    ];
    $record = new Fluent($personRecord);

    $record->address('hometown, street, house');

    echo $record->first_name . "\n";
    echo $record->address . "\n";

    $messageBag->add('notice', 'Fluent displayed.');

    echo '</pre><hr>';


    // Pluralizer
    echo '<h2>Pluralizer</h2>';
    echo '<pre>';

    $item = 'goose';
    echo "One $item, two " . Pluralizer::plural($item) . "\n";
    $item = 'moose';
    echo "One $item, two " . Pluralizer::plural($item) . "\n";

    echo '</pre><hr>';

    // Str
    echo '<h2>Str</h2>';
    echo '<pre>';

    if (Str::contains('This is my fourteenth visit', 'first')) {
        echo 'Howdy!';
    } else {
        echo 'Nice to see you again.';
    }

    echo '</pre><hr>';

    echo '<h2>MessageBag</h2>';
    echo '<pre>';

    echo "MessageBag ({$messageBag->count()})\n";
    foreach ($messageBag->all() as $message) {
        echo " - $message\n";
    }
});

$app->run();
