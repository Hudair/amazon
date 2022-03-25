<?php

namespace Incevio\Package\Wallet\Simple;

use Incevio\Package\Wallet\Interfaces\Rateable;
// use Incevio\Package\Wallet\Interfaces\Wallet;

/**
 * Class Rate.
 */
class Rate implements Rateable
{
    /**
     * @var int
     */
    protected $amount;

    /**
     * @var Wallet|\Incevio\Package\Wallet\Models\Wallet
     */
    protected $withCurrency;

    /**
     * {@inheritdoc}
     */
    public function withAmount($amount): Rateable
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withCurrency($wallet): Rateable
    {
        $this->withCurrency = $wallet;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function convertTo($wallet)
    {
        return $this->amount;
    }
}
