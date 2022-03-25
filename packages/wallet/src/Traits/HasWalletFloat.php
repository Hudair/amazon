<?php

namespace Incevio\Package\Wallet\Traits;

use Incevio\Package\Wallet\Interfaces\Mathable;
// use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Models\Transfer;
use Incevio\Package\Wallet\Services\WalletService;

/**
 * Trait HasWalletFloat.
 *
 *
 * @property-read float $balanceFloat
 */
trait HasWalletFloat
{
    use HasWallet;

    /**
     * @param float $amount
     * @param array|null $meta
     * @param bool $confirmed
     *
     * @return Transaction
     */
    public function forceWithdrawFloat($amount, ?array $meta = null, bool $confirmed = true): Transaction
    {
        $math = app(Mathable::class);
        $decimalPlacesValue = app(WalletService::class)->decimalPlacesValue($this);
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this);
        $result = $math->round($math->mul($amount, $decimalPlaces, $decimalPlacesValue));

        return $this->forceWithdraw($result, $meta, $confirmed);
    }

    /**
     * @param float $amount
     * @param array|null $meta
     * @param bool $confirmed
     *
     * @return Transaction
     */
    public function depositFloat($amount, ?array $meta = null, bool $confirmed = true): Transaction
    {
        $math = app(Mathable::class);
        $decimalPlacesValue = app(WalletService::class)->decimalPlacesValue($this);
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this);
        $result = $math->round($math->mul($amount, $decimalPlaces, $decimalPlacesValue));

        return $this->deposit($result, $meta, $confirmed);
    }

    /**
     * @param float $amount
     * @param array|null $meta
     * @param bool $confirmed
     *
     * @return Transaction
     */
    public function withdrawFloat($amount, ?array $meta = null, bool $confirmed = true): Transaction
    {
        $math = app(Mathable::class);
        $decimalPlacesValue = app(WalletService::class)->decimalPlacesValue($this);
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this);
        $result = $math->round($math->mul($amount, $decimalPlaces, $decimalPlacesValue));

        return $this->withdraw($result, $meta, $confirmed);
    }

    /**
     * @param float $amount
     * @return bool
     */
    public function canWithdrawFloat($amount): bool
    {
        $math = app(Mathable::class);
        $decimalPlacesValue = app(WalletService::class)->decimalPlacesValue($this);
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this);
        $result = $math->round($math->mul($amount, $decimalPlaces, $decimalPlacesValue));

        return $this->canWithdraw($result);
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param array|null $meta
     * @return Transfer
     * @throws
     */
    public function transferFloat($wallet, $amount, ?array $meta = null): Transfer
    {
        $math = app(Mathable::class);
        $decimalPlacesValue = app(WalletService::class)->decimalPlacesValue($this);
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this);
        $result = $math->round($math->mul($amount, $decimalPlaces, $decimalPlacesValue));

        return $this->transfer($wallet, $result, $meta);
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param array|null $meta
     * @return null|Transfer
     */
    public function safeTransferFloat($wallet, $amount, ?array $meta = null): ?Transfer
    {
        $math = app(Mathable::class);
        $decimalPlacesValue = app(WalletService::class)->decimalPlacesValue($this);
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this);
        $result = $math->round($math->mul($amount, $decimalPlaces, $decimalPlacesValue));

        return $this->safeTransfer($wallet, $result, $meta);
    }

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param array|null $meta
     * @return Transfer
     */
    public function forceTransferFloat($wallet, $amount, ?array $meta = null): Transfer
    {
        $math = app(Mathable::class);
        $decimalPlacesValue = app(WalletService::class)->decimalPlacesValue($this);
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this);
        $result = $math->round($math->mul($amount, $decimalPlaces, $decimalPlacesValue));

        return $this->forceTransfer($wallet, $result, $meta);
    }

    /**
     * @return int|float
     */
    public function getBalanceFloatAttribute()
    {
        $math = app(Mathable::class);
        $decimalPlacesValue = app(WalletService::class)->decimalPlacesValue($this);
        $decimalPlaces = app(WalletService::class)->decimalPlaces($this);

        return $math->div($this->balance, $decimalPlaces, $decimalPlacesValue);
    }
}
