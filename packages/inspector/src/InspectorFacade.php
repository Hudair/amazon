<?php

namespace Incevio\Package\Inspector;

use Illuminate\Support\Facades\Facade;

class InspectorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'inspector';
    }
}
