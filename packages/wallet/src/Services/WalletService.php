<?php

namespace Incevio\Package\Wallet\Services;

use function app;
use Incevio\Package\Wallet\Exceptions\AmountInvalid;
use Incevio\Package\Wallet\Interfaces\Customer;
use Incevio\Package\Wallet\Interfaces\Discount;
use Incevio\Package\Wallet\Interfaces\Mathable;
use Incevio\Package\Wallet\Interfaces\MinimalTaxable;
use Incevio\Package\Wallet\Interfaces\Storable;
use Incevio\Package\Wallet\Interfaces\Taxable;
// use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Models\Wallet as WalletModel;
use Incevio\Package\Wallet\Traits\HasWallet;

class WalletService
{
    /**
     * @param Wallet $customer
     * @param Wallet $product
     * @return int
     */
    public function discount($customer, $product): int
    {
        if ($customer instanceof Customer && $product instanceof Discount) {
            return $product->getPersonalDiscount($customer);
        }

        // without discount
        return 0;
    }

    /**
     * @param Wallet $wallet
     * @return int
     */
    public function decimalPlacesValue($wallet): int
    {
        return config('system_settings.decimals', 2);
    }

    /**
     * @param Wallet $wallet
     * @return string
     */
    public function decimalPlaces($wallet): string
    {
        return app(Mathable::class)->pow(10, $this->decimalPlacesValue($wallet));
    }

    /**
     * Consider the fee that the system will receive.
     *
     * @param Wallet $wallet
     * @param int $amount
     * @return float|int
     */
    public function fee($wallet, $amount)
    {
        $fee = 0;
        $math = app(Mathable::class);
        if ($wallet instanceof Taxable) {
            $placesValue = $this->decimalPlacesValue($wallet);
            $fee = $math->floor(
                $math->div(
                    $math->mul($amount, $wallet->getFeePercent(), 0),
                    100,
                    $placesValue
                )
            );
        }

        /**
         * Added minimum commission condition.
         */
        if ($wallet instanceof MinimalTaxable) {
            $minimal = $wallet->getMinimalFee();
            if (app(Mathable::class)->compare($fee, $minimal) === -1) {
                $fee = $minimal;
            }
        }

        return $fee;
    }

    /**
     * The amount of checks for errors.
     *
     * @param int $amount
     * @throws
     */
    public function checkAmount($amount): void
    {
        if(app(Mathable::class)->compare($amount, 0) === -1) {
            throw new AmountInvalid(trans('wallet::lang.price_positive'));
        }
    }

    /**
     * @param Wallet $object
     * @param bool $autoSave
     * @return WalletModel
     */
    public function getWallet($object, bool $autoSave = true): WalletModel
    {
        /**
         * @var WalletModel $wallet
         */
        $wallet = $object;

        if (! ($object instanceof WalletModel)) {
            /**
             * @var HasWallet $object
             */
            $wallet = $object->wallet;
        }

        if ($autoSave) {
            $wallet->exists or $wallet->save();
        }

        return $wallet;
    }

    /**
     * @param WalletModel $wallet
     * @return bool
     */
    public function refresh(WalletModel $wallet): bool
    {
        return app(LockService::class)->lock($this, __FUNCTION__, static function () use ($wallet) {
            $math = app(Mathable::class);
            app(Storable::class)->getBalance($wallet);
            $whatIs = $wallet->balance;
            $balance = $wallet->getAvailableBalance();
            $wallet->balance = $balance;

            return app(Storable::class)->setBalance($wallet, $balance) &&
                (! $math->compare($whatIs, $balance) || $wallet->save());
        });
    }
}
