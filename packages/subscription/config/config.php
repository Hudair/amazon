<?php

// use Incevio\Package\Wallet\Models\Transaction;

return [

    /**
     * This values will be used as default for the package
     */
    'default' => [
        'charge_min_before_expiry' => 5,
    ],

    // 'routes' => [
        // 'wallet' => 'merchant.account.wallet',
        // 'deposit_form' => 'merchant.account.wallet.deposit.form',
        // 'withdrawal_form' => 'merchant.account.wallet.withdrawal.form',
    // ],

    /**
     * Sometimes a slug may not match the currency and you need the ability to add an exception.
     * The main thing is that there are not many exceptions).
     */
    // 'currencies' => [],
];
