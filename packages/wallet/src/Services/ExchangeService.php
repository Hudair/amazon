<?php

namespace Incevio\Package\Wallet\Services;

use Incevio\Package\Wallet\Interfaces\Rateable;
// use Incevio\Package\Wallet\Interfaces\Wallet;

class ExchangeService
{
    /**
     * @param Wallet $from
     * @param Wallet $to
     * @return int|float
     */
    public function rate($from, $to)
    {
        return app(Rateable::class)
            ->withAmount(1)
            ->withCurrency($from)
            ->convertTo($to);
    }
}
