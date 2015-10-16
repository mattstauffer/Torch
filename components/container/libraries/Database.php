<?php

namespace Acme;

class Database
{
    public function select($query)
    {
        return [
            ['title' => 'Example article title'],
            ['title' => 'Another example article title'],
        ];
    }
}
