<?php

namespace App\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate as AccessGate;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersController
{
    public function index()
    {
        return "listing the users<br><br><form method='post'><input type='submit'></form>";
    }

    public function store(Request $request)
    {
        $container = App::getInstance();

        $user = $container['auth']->user();

        if (! $container[AccessGate::class]->allows('add-user', $user)) {
            throw new HttpException(403);
        }

        return "creating new user";
    }
}
