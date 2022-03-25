<?php

namespace Incevio\Package\Wallet\Interfaces;

interface MinimalTaxable extends Taxable
{
    /**
     * @return int|float
     */
    public function getMinimalFee();
}
