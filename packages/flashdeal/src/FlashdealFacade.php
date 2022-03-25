<?php

namespace Incevio\Package\Flashdeal;

use Illuminate\Support\Facades\Facade;

class FlashdealFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'flashdeal';
    }
}
