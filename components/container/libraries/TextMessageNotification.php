<?php

namespace Acme;

use Acme\Contracts\NotifyUser;

class TextMessageNotification implements NotifyUser
{
    public function sendNotification($message)
    {
        echo "Your text message notification: $message";
    }  
}