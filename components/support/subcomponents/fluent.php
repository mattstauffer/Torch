<?php

use Illuminate\Support\Fluent;


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

echo '</pre><hr>';