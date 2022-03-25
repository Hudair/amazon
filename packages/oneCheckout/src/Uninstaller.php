<?php

namespace Incevio\Package\Checkout;

class Uninstaller
{
    public $package;

    public function __construct()
    {
        $this->package = 'Checkout';
    }

    public function cleanDatabase()
    {
        return true;
    }
}
