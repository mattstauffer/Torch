<?php

namespace Acme;

class Authentication
{
    public function verifyLogin($username, $password)
    {
        return $username === 'username' and $password === 'password';
    }
}
