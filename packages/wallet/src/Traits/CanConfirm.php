<?php

namespace Incevio\Package\Wallet\Traits;

use Incevio\Package\Wallet\Exceptions\ConfirmedInvalid;
use Incevio\Package\Wallet\Exceptions\WalletOwnerInvalid;
use Incevio\Package\Wallet\Interfaces\Mathable;
use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Services\CommonService;
use Incevio\Package\Wallet\Services\LockService;
use Incevio\Package\Wallet\Services\WalletService;
use App\Services\DbService;

trait CanConfirm
{
    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function confirm(Transaction $transaction): bool
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($transaction) {
            $self = $this;

            return DbService::transaction(static function () use ($self, $transaction) {
                // $wallet = $transaction->wallet;
                // $wallet = app(WalletService::class)->getWallet($self);
                if (! $self->refreshBalance()) {
                    return false;
                }

                // echo "<pre>"; print_r($self->toArray()); echo "<pre>"; exit('end!');

                if ($transaction->type === Transaction::TYPE_WITHDRAW) {
                    app(CommonService::class)->verifyWithdraw(
                        $self, app(Mathable::class)->abs($transaction->amount)
                    );
                }

                return $self->forceConfirm($transaction);
            });
        });
    }

    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function safeConfirm(Transaction $transaction): bool
    {
        try {
            return $this->confirm($transaction);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * Removal of confirmation (forced), use at your own peril and risk.
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function resetConfirm(Transaction $transaction): bool
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($transaction) {
            $self = $this;

            return DbService::transaction(static function () use ($self, $transaction) {
                // $wallet = app(WalletService::class)->getWallet($self);
                if (! $self->refreshBalance()) {
                    return false;
                }

                if (! $transaction->confirmed) {
                    throw new ConfirmedInvalid(trans('wallet::lang.unconfirmed_invalid'));
                }

                return $transaction->update(['confirmed' => false]) &&

                    // update balance
                    app(CommonService::class)->addBalance($self, -$transaction->amount);
            });
        });
    }

    /**
     * @param Transaction $transaction
     * @return bool
     */
    public function safeResetConfirm(Transaction $transaction): bool
    {
        try {
            return $this->resetConfirm($transaction);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * @param Transaction $transaction
     * @return bool
     * @throws ConfirmedInvalid
     * @throws WalletOwnerInvalid
     */
    public function forceConfirm(Transaction $transaction): bool
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($transaction) {
            $self = $this;

            return DbService::transaction(static function () use ($self, $transaction) {
                // $wallet = $transaction->wallet;
                // $wallet = app(WalletService::class)->getWallet($self);

                if ($transaction->confirmed) {
                    throw new ConfirmedInvalid(trans('wallet::lang.confirmed_invalid'));
                }

                if ($self->getKey() !== $transaction->wallet_id) {
                    throw new WalletOwnerInvalid(trans('wallet::lang.owner_invalid'));
                }

                return $transaction->update(['confirmed' => true]) &&

                    // update balance
                    app(CommonService::class)->addBalance($self, $transaction->amount);
            });
        });
    }
}
