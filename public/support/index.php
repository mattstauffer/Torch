<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str;

require_once '../../vendor/autoload.php';

/**
 * Illuminate/support
 *
 * Provides array helpers, Collection, Fluent, Pluralizer, Str, MessageBag, and more
 *
 * Requires: illuminate/support
 *
 * @source https://github.com/illuminate/support
 * @see http://www.gufran.me/post/laravel-illuminate-support-package-introduction
 * @see http://daylerees.com/codebright/eloquent-collections
 */

$app = new \Slim\Slim();
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);

$app->get('/', function () {
	echo '<pre>';

	// MessageBag init
	$messageBag = new MessageBag;


	// Array dot notation (and other helpers)
	$person = [
		'name' => [
			'first' => 'Jill',
			'last' => 'Schmoe'
		]
	];
	echo 'name.first is ' . array_get($person, 'name.first') . "\n";

	$messageBag->add('notice', 'Array dot notation displayed.');

	echo '<hr>';


	// Collection
	$people = new Collection(['Declan', 'Abner', 'Mitzi']);

	$people->each(function($person) {
		echo "Collection person: $person\n";
	});

	$messageBag->add('notice', 'Collection displayed.');

	echo '<hr>';

	// More at http://daylerees.com/codebright/eloquent-collections


	// Fluent
	$personRecord = array(
		'first_name' => 'Mohammad',
		'last_name' => 'Gufran'
	);
	$record = new Fluent($personRecord);

	$record->address('hometown, street, house');

	echo $record->first_name . "\n";
	echo $record->address . "\n";

	$messageBag->add('notice', 'Fluent displayed.');

	echo '<hr>';


	// Pluralizer
	$item = 'goose';
	echo "One $item, two " . Pluralizer::plural($item) . "\n";
	$item = 'moose';
	echo "One $item, two " . Pluralizer::plural($item) . "\n";

	echo '<hr>';

	// Str
	if (Str::contains('This is my fourteenth visit', 'first')) {
		echo 'Howdy!';
	} else {
		echo 'Nice to see you again.';
	}

	echo '<hr>';


	echo "MessageBag ({$messageBag->count()})\n";
	foreach ($messageBag->all() as $message) {
		echo " - $message\n";
	}

});

$app->run();
