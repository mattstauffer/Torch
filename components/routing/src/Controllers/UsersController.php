<?php

namespace App\Controllers;

class UsersController
{
    public function index()
    {
        return "listing the users<br><br><form method='post'><input type='submit'></form>";
    }

    public function store()
    {
        return 'creating new user';
    }
}
