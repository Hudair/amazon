<?php

namespace Incevio\Package\Wallet\Traits;

use Incevio\Package\Wallet\Interfaces\Mathable;
// use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Models\Transfer;
use Incevio\Package\Wallet\Objects\Bring;
use Incevio\Package\Wallet\Services\CommonService;
use App\Services\DbService;
use Incevio\Package\Wallet\Services\ExchangeService;
use Incevio\Package\Wallet\Services\LockService;
use Incevio\Package\Wallet\Services\WalletService;

trait CanExchange
{
    /**
     * {@inheritdoc}
     */
    public function exchange($to, $amount, ?array $meta = null): Transfer
    {
        $wallet = app(WalletService::class)->getWallet($this);

        app(CommonService::class)->verifyWithdraw($wallet, $amount);

        return $this->forceExchange($to, $amount, $meta);
    }

    /**
     * {@inheritdoc}
     */
    public function safeExchange($to, $amount, ?array $meta = null): ?Transfer
    {
        try {
            return $this->exchange($to, $amount, $meta);
        }
        catch (\Throwable $throwable) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function forceExchange($to, $amount, ?array $meta = null): Transfer
    {
        /**
         * @var Wallet $from
         */
        $from = app(WalletService::class)->getWallet($this);

        return app(LockService::class)->lock($this, __FUNCTION__, static function () use ($from, $to, $amount, $meta) {
            return DbService::transaction(static function () use ($from, $to, $amount, $meta) {
                $math = app(Mathable::class);
                $rate = app(ExchangeService::class)->rate($from, $to);
                $fee = app(WalletService::class)->fee($to, $amount);

                $withdraw = app(CommonService::class)->forceWithdraw($from, $math->add($amount, $fee), $meta);

                $deposit = app(CommonService::class)->deposit($to, $math->floor($math->mul($amount, $rate, 1)), $meta);

                $transfers = app(CommonService::class)->multiBrings([
                    app(Bring::class)
                        ->setDiscount(0)
                        ->setStatus(Transfer::STATUS_EXCHANGE)
                        ->setDeposit($deposit)
                        ->setWithdraw($withdraw)
                        ->setFrom($from)
                        ->setFee($fee)
                        ->setTo($to),
                ]);

                return current($transfers);
            });
        });
    }
}
