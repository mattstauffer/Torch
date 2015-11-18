<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class EncapsulatedEloquentBase.
 *
 * @author Kayla Daniels
 */
abstract class EncapsulatedEloquentBase extends Eloquent
{
    public function __construct(array $attributes = [])
    {
        Encapsulator::init();

        parent::__construct($attributes);
    }
}
