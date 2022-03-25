<?php

namespace Incevio\Package\DynamicCommission;

use Illuminate\Support\Facades\Facade;

class DynamicCommissionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dynamicCommission';
    }
}
