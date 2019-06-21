<?php

namespace harmonic\Ezypay\Facades;

use Illuminate\Support\Facades\Facade;
use harmonic\Ezypay\Testing\EzypayFake;

class Ezypay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ezypay';
    }

    /**
     * Replace the bound instance with a fake.
     *
     * @param  array|string  $commandsToFake
     * @return void
     */
    public static function fake($commandsToFake = [])
    {
        static::swap(new EzypayFake($commandsToFake));
    }
}
