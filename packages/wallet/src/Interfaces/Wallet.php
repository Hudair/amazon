<?php

namespace Incevio\Package\Wallet\Interfaces;

use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Models\Transfer;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Wallet
{
    /**
     * @param int $amount
     * @param array|null $meta
     * @param bool $confirmed
     * @return Transaction
     */
    public function deposit($amount, ?array $meta = null, bool $confirmed = true): Transaction;

    /**
     * @param int $amount
     * @param array|null $meta
     * @param bool $confirmed
     * @return Transaction
     */
    public function withdraw($amount, ?array $meta = null, bool $confirmed = true, bool $approved = true): Transaction;

    /**
     * @param int $amount
     * @param array|null $meta
     * @param bool $confirmed
     * @return Transaction
     */
    public function forceWithdraw($amount, ?array $meta = null, bool $confirmed = true): Transaction;

    /**
     * @param self $wallet
     * @param int $amount
     * @param array|null $meta
     * @return Transfer
     */
    public function transfer(self $wallet, $amount, ?array $meta = null): Transfer;

    /**
     * @param self $wallet
     * @param int $amount
     * @param array|null $meta
     * @return null|Transfer
     */
    public function safeTransfer(self $wallet, $amount, ?array $meta = null): ?Transfer;

    /**
     * @param Wallet $wallet
     * @param int $amount
     * @param array|null $meta
     * @return Transfer
     */
    public function forceTransfer(Wallet $wallet, $amount, ?array $meta = null): Transfer;

    /**
     * @param int $amount
     * @param bool $allowZero
     * @return bool
     */
    public function canWithdraw($amount, bool $allowZero = null): bool;

    /**
     * @return int|float
     */
    public function getBalanceAttribute();

    /**
     * @return MorphMany
     */
    public function transactions(): MorphMany;
}
