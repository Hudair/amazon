<?php

namespace Incevio\Package\Wallet\Interfaces;

use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Models\Transfer;

interface WalletFloat
{
    /**
     * @param float $amount
     * @param array|null $meta
     * @param bool $confirmed
     * @return Transaction
     */
    public function depositFloat($amount, ?array $meta = null, bool $confirmed = true): Transaction;

    /**
     * @param float $amount
     * @param array|null $meta
     * @param bool $confirmed
     * @return Transaction
     */
    public function withdrawFloat($amount, ?array $meta = null, bool $confirmed = true): Transaction;

    /**
     * @param float $amount
     * @param array|null $meta
     * @param bool $confirmed
     * @return Transaction
     */
    public function forceWithdrawFloat($amount, ?array $meta = null, bool $confirmed = true): Transaction;

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param array|null $meta
     * @return Transfer
     */
    public function transferFloat(Wallet $wallet, $amount, ?array $meta = null): Transfer;

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param array|null $meta
     * @return null|Transfer
     */
    public function safeTransferFloat(Wallet $wallet, $amount, ?array $meta = null): ?Transfer;

    /**
     * @param Wallet $wallet
     * @param float $amount
     * @param array|null $meta
     * @return Transfer
     */
    public function forceTransferFloat(Wallet $wallet, $amount, ?array $meta = null): Transfer;

    /**
     * @param float $amount
     * @return bool
     */
    public function canWithdrawFloat($amount): bool;

    /**
     * @return int|float
     */
    public function getBalanceFloatAttribute();
}
