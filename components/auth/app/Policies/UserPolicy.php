<?php

namespace App\Policies;

use App\Eloquent\User;

class UserPolicy
{
    public function add(User $user)
    {
        return $user->email === 'user';
    }
}
