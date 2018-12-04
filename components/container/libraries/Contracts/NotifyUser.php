<?php

namespace Acme\Contracts;

interface NotifyUser
{
    public function sendNotification($message);
}