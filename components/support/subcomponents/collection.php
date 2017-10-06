<?php

use Illuminate\Support\Collection;


// Collection
echo '<h2>Collection</h2>';
echo '<pre>';
$people = new Collection(['Declan', 'Abner', 'Mitzi']);

$people->map(function ($person) {
    return "<i>$person</i>";
})->each(function ($person) {
    echo "Collection person: $person\n";
});

echo '</pre><hr>';