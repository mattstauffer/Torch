<?php

namespace App\Controllers;

use Illuminate\Http\Request;

class UsersController
{
    public function index()
    {
        return "
            listing the users
            <br>
            <br>
            <form method='post'>
            <input type='text' name='name'>
            <input type='submit'>
            </form>";
    }

    public function store(Request $request)
    {
        $name = $request->input('name');

        return "creating new user named $name";
    }
}
