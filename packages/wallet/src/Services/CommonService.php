<?php

namespace Incevio\Package\Wallet\Services;

use function app;
use Incevio\Package\Wallet\Exceptions\BalanceIsEmpty;
use Incevio\Package\Wallet\Exceptions\InsufficientFunds;
use Incevio\Package\Wallet\Interfaces\Mathable;
use Incevio\Package\Wallet\Interfaces\Storable;
// use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Models\Transfer;
use Incevio\Package\Wallet\Models\Wallet as WalletModel;
use Incevio\Package\Wallet\Objects\Bring;
use Incevio\Package\Wallet\Objects\Operation;
use Incevio\Package\Wallet\Traits\HasWallet;
use App\Services\DbService;
use function compact;
use function max;

class CommonService
{
    /**
     * @param Wallet $from
     * @param Wallet $to
     * @param int $amount
     * @param array|null $meta
     * @param string $status
     * @return Transfer
     */
    public function transfer($from, $to, $amount, ?array $meta = null, string $status = Transfer::STATUS_TRANSFER): Transfer
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($from, $to, $amount, $meta, $status) {
            $math = app(Mathable::class);
            $discount = app(WalletService::class)->discount($from, $to);
            $newAmount = max(0, $math->sub($amount, $discount));
            $fee = app(WalletService::class)->fee($to, $newAmount);
            $this->verifyWithdraw($from, $math->add($newAmount, $fee));

            return $this->forceTransfer($from, $to, $amount, $meta, $status);
        });
    }

    /**
     * @param Wallet $from
     * @param Wallet $to
     * @param int $amount
     * @param array|null $meta
     * @param string $status
     * @return Transfer
     */
    public function forceTransfer($from, $to, $amount, ?array $meta = null, string $status = Transfer::STATUS_TRANSFER): Transfer
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($from, $to, $amount, $meta, $status) {
            $math = app(Mathable::class);
            $from = app(WalletService::class)->getWallet($from);
            $discount = app(WalletService::class)->discount($from, $to);
            $amount = max(0, $math->sub($amount, $discount));

            $fee = app(WalletService::class)->fee($to, $amount);
            $placesValue = app(WalletService::class)->decimalPlacesValue($from);

            $fromMeta = (!empty($meta['from'])) ? $meta['from'] : $meta;
            $toMeta = (!empty($meta['to'])) ? $meta['to'] : $meta;

            $withdraw = $this->forceWithdraw($from, $math->add($amount, $fee, $placesValue), $fromMeta);
            $deposit = $this->deposit($to, $amount, $toMeta);

            $transfers = $this->multiBrings([
                app(Bring::class)
                    ->setStatus($status)
                    ->setDeposit($deposit)
                    ->setWithdraw($withdraw)
                    ->setDiscount($discount)
                    ->setFrom($from)
                    ->setTo($to),
            ]);

            return current($transfers);
        });
    }

    /**
     * @param Wallet $wallet
     * @param int $amount
     * @param array|null $meta
     * @param bool|null $confirmed
     * @return Transaction
     */
    public function forceWithdraw($wallet, $amount, ?array $meta, bool $confirmed = true, bool $approved = true): Transaction
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($wallet, $amount, $meta, $confirmed, $approved) {
            $walletService = app(WalletService::class);
            $walletService->checkAmount($amount);

            /**
             * @var WalletModel $wallet
             */
            $wallet = $walletService->getWallet($wallet);
            /**
             * Set Remaining Balance
             * */
            $balance = ($wallet->balance - $amount);

            $transactions = $this->multiOperation($wallet, [
                app(Operation::class)
                    ->setType(Transaction::TYPE_WITHDRAW)
                    ->setConfirmed($confirmed)
                    ->setApproved($approved)
                    ->setAmount(-$amount)
                    ->setBalance($balance)
                    ->setMeta($meta),
            ]);

            return current($transactions);
        });
    }

    /**
     * @param Wallet $wallet
     * @param int $amount
     * @param array|null $meta
     * @param bool $confirmed
     * @return Transaction
     */
    public function deposit($wallet, $amount, ?array $meta, bool $confirmed = true, bool $approved = true): Transaction
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($wallet, $amount, $meta, $confirmed, $approved) {
            $walletService = app(WalletService::class);
            $walletService->checkAmount($amount);

            /**
             * @var WalletModel $wallet
             */
            $wallet = $walletService->getWallet($wallet);
            /**
             * Set Remaining Balance
             * */
            $balance = ($wallet->balance + $amount);

            $transactions = $this->multiOperation($wallet, [
                app(Operation::class)
                    ->setType(Transaction::TYPE_DEPOSIT)
                    ->setConfirmed($confirmed)
                    ->setApproved($approved)
                    ->setAmount($amount)
                    ->setBalance($balance)
                    ->setMeta($meta),
            ]);

            return current($transactions);
        });
    }

    /**
     * @param Wallet $wallet
     * @param int $amount
     * @param bool $allowZero
     * @return void
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     */
    public function verifyWithdraw($wallet, $amount, bool $allowZero = null): void
    {
        /**
         * @var HasWallet $wallet
         */
        if ($amount && ! $wallet->balance) {
            throw new BalanceIsEmpty(trans('wallet::lang.wallet_empty'));
        }

        if (! $wallet->canWithdraw($amount, $allowZero)) {
            throw new InsufficientFunds(trans('wallet::lang.insufficient_funds'));
        }
    }

    /**
     * Create Operation without DB::transaction.
     *
     * @param Wallet $self
     * @param Operation[] $operations
     * @return array
     */
    public function multiOperation($self, array $operations): array
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($self, $operations) {
            $amount = 0;
            $objects = [];
            $math = app(Mathable::class);
            foreach ($operations as $operation) {
                if ($operation->isConfirmed()) {
                    $amount = $math->add($amount, $operation->getAmount());
                }

                $objects[] = $operation
                    ->setWallet($self)
                    ->create();
            }

            $this->addBalance($self, $amount);

            return $objects;
        });
    }

    /**
     * Create Bring with DB::transaction.
     *
     * @param Bring[] $brings
     * @return array
     * @throws
     */
    public function assemble(array $brings): array
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($brings) {
            $self = $this;

            return DbService::transaction(static function () use ($self, $brings) {
                return $self->multiBrings($brings);
            });
        });
    }

    /**
     * Create Bring without DB::transaction.
     *
     * @param array $brings
     * @return array
     */
    public function multiBrings(array $brings): array
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($brings) {
            $objects = [];
            foreach ($brings as $bring) {
                $objects[] = $bring->create();
            }

            return $objects;
        });
    }

    /**
     * @param Wallet $wallet
     * @param int $amount
     * @return bool
     * @throws
     */
    public function addBalance($wallet, $amount): bool
    {
        return app(LockService::class)->lock($this, __FUNCTION__, static function () use ($wallet, $amount) {
            /**
             * @var WalletModel $wallet
             */
            $balance = app(Storable::class)
                ->incBalance($wallet, $amount);

            try {
                $result = $wallet->update(compact('balance'));
            } catch (\Throwable $throwable) {
                app(Storable::class)
                    ->setBalance($wallet, $wallet->getAvailableBalance());

                throw $throwable;
            }

            if (! $result) {
                app(Storable::class)
                    ->setBalance($wallet, $wallet->getAvailableBalance());
            }

            return $result;
        });
    }
}
