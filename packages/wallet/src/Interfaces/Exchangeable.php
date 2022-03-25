<?php

namespace Incevio\Package\Wallet\Interfaces;

use Incevio\Package\Wallet\Models\Transfer;

interface Exchangeable
{
    /**
     * @param Wallet $to
     * @param int $amount
     * @param array|null $meta
     * @return Transfer
     */
    public function exchange(Wallet $to, $amount, ?array $meta = null): Transfer;

    /**
     * @param Wallet $to
     * @param int $amount
     * @param array|null $meta
     * @return Transfer|null
     */
    public function safeExchange(Wallet $to, $amount, ?array $meta = null): ?Transfer;

    /**
     * @param Wallet $to
     * @param int $amount
     * @param array|null $meta
     * @return Transfer
     */
    public function forceExchange(Wallet $to, $amount, ?array $meta = null): Transfer;
}
