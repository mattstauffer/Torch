<?php

namespace Torch\Routing\Controllers;

class DashboardController
{
    public function login(): string
    {
        $_SESSION['logged_in'] = true;

        return 'Successfully logged in! <a href="/dashboard">View Dashboard</a>';
    }

    public function logout(): string
    {
        unset($_SESSION['logged_in']);

        return 'Successfully logged out. <a href="/">Return to Home</a>';
    }

    public function dashboard(): string
    {
        return 'This is the dashboard for logged-in users. <a href="/logout">Log Out</a>';
    }
}
