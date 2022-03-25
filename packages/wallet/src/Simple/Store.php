<?php

namespace Incevio\Package\Wallet\Simple;

use Incevio\Package\Wallet\Interfaces\Mathable;
use Incevio\Package\Wallet\Interfaces\Storable;
use Incevio\Package\Wallet\Services\WalletService;

class Store implements Storable
{
    /**
     * @var array
     */
    protected $balanceSheets = [];

    /**
     * {@inheritdoc}
     */
    public function getBalance($object)
    {
        $wallet = app(WalletService::class)->getWallet($object);

        if (! \array_key_exists($wallet->getKey(), $this->balanceSheets)) {
            $balance = method_exists($wallet, 'getRawOriginal') ?
                $wallet->getRawOriginal('balance', 0) : $wallet->getOriginal('balance', 0);

            $this->balanceSheets[$wallet->getKey()] = $balance;
            // $this->balanceSheets[$wallet->getKey()] = $this->toInt($balance);
        }

        return $this->balanceSheets[$wallet->getKey()];
    }

    /**
     * {@inheritdoc}
     */
    public function incBalance($object, $amount)
    {
        $math = app(Mathable::class);
        $balance = $math->add($this->getBalance($object), $amount);
        // $balance = $this->toInt($balance);
        $this->setBalance($object, $balance);

        return $balance;
    }

    /**
     * {@inheritdoc}
     */
    public function setBalance($object, $amount): bool
    {
        $wallet = app(WalletService::class)->getWallet($object);
        $this->balanceSheets[$wallet->getKey()] = $amount;
        // $this->balanceSheets[$wallet->getKey()] = $this->toInt($amount);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function fresh(): bool
    {
        $this->balanceSheets = [];

        return true;
    }

    /**
     * @param string $balance
     * @return string
     */
    protected function toInt($balance): string
    {
        return app(Mathable::class)->round($balance ?: 0);
    }
}
