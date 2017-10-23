<?php

use Illuminate\Support\MessageBag;



$messageBag = new MessageBag;

$messageBag->add('notice', 'Array dot notation displayed.');

$messageBag->add('notice', 'Collection displayed.');

$messageBag->add('notice', 'Fluent displayed.');

echo '<h2>MessageBag</h2>';
echo '<pre>';

echo "MessageBag ({$messageBag->count()})\n";
foreach ($messageBag->all() as $message) {
    echo " - $message\n";
}