<?php

namespace Torch;

use Illuminate\Container\Container;

class App extends Container
{
    /**
     * @return bool
     *
     * @see \Illuminate\Contracts\Foundation\Application::isDownForMaintenance()
     */
    public function isDownForMaintenance(): bool
    {
        return false;
    }

    /**
     * @param  string|array  $environments
     * @return string|bool
     *
     * @see \Illuminate\Contracts\Foundation\Application::environment()
     */
    public function environment(...$environments)
    {
        if(!empty($environments)) {
            return in_array('torch', $environments);
        }

        return 'torch';
    }
}