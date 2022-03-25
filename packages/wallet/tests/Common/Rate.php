<?php

namespace Incevio\Package\Wallet\Test\Common;

use Incevio\Package\Wallet\Interfaces\Mathable;
// use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Services\WalletService;
use Illuminate\Support\Arr;

class Rate extends \Incevio\Package\Wallet\Simple\Rate
{
    /**
     * @var array
     */
    protected $rates = [
        'USD' => [
            'RUB' => 67.61,
        ],
    ];

    /**
     * Rate constructor.
     */
    public function __construct()
    {
        foreach ($this->rates as $from => $rates) {
            foreach ($rates as $to => $rate) {
                if (empty($this->rates[$to][$from])) {
                    $this->rates[$to][$from] = app(Mathable::class)->div(1, $rate);
                }
            }
        }
    }

    /**
     * @param Wallet $wallet
     * @return int|float
     */
    protected function rate($wallet)
    {
        $from = app(WalletService::class)->getWallet($this->withCurrency);
        $to = app(WalletService::class)->getWallet($wallet);

        return Arr::get(Arr::get($this->rates, $from->currency, []), $to->currency, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function convertTo($wallet)
    {
        return app(Mathable::class)->mul(parent::convertTo($wallet), $this->rate($wallet));
    }
}
