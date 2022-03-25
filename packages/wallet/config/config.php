<?php

use Incevio\Package\Wallet\Models\Transaction;
use Incevio\Package\Wallet\Models\Transfer;
use Incevio\Package\Wallet\Models\Wallet;
use Incevio\Package\Wallet\Objects\Bring;
use Incevio\Package\Wallet\Objects\Cart;
use Incevio\Package\Wallet\Objects\EmptyLock;
use Incevio\Package\Wallet\Objects\Operation;
use Incevio\Package\Wallet\Services\CommonService;
use Incevio\Package\Wallet\Services\ExchangeService;
use Incevio\Package\Wallet\Services\LockService;
use Incevio\Package\Wallet\Services\WalletService;
use Incevio\Package\Wallet\Simple\BCMath;
use Incevio\Package\Wallet\Simple\BrickMath;
use Incevio\Package\Wallet\Simple\Math;
use Incevio\Package\Wallet\Simple\Rate;
use Incevio\Package\Wallet\Simple\Store;
use Brick\Math\BigDecimal;

$bcLoaded = extension_loaded('bcmath');
$mathClass = Math::class;
switch (true) {
    case class_exists(BigDecimal::class):
        $mathClass = BrickMath::class;
        break;
    case $bcLoaded:
        $mathClass = BCMath::class;
        break;
}

return [

    /**
     * This values will be used as default for the package
     */
    'default' => [
        'min_withdrawal_limit' => 100,
    ],

    'routes' => [
        'wallet' => 'account.wallet',
        'deposit_form' => 'account.wallet.deposit.form',
        'transfer_form' => 'account.wallet.transfer.form',
        'withdrawal_form' => 'account.wallet.withdrawal.form',
        'settings' => 'admin.wallet.settings'
    ],

    /**
     * This parameter is necessary for more accurate calculations.
     * PS, Arbitrary Precision Calculations.
     */
    'math' => [
        'scale' => 64,
    ],

    /**
     * The parameter is used for fast packet overload.
     * You do not need to search for the desired class by code, the library will do it itself.
     */
    'package' => [
        'rateable' => Rate::class,
        'storable' => Store::class,
        'mathable' => $mathClass,
    ],

    /**
     * Lock settings for highload projects.
     *
     * If you want to replace the default cache with another,
     * then write the name of the driver cache in the key `wallet.lock.cache`.
     * @see https://laravel.com/docs/6.x/cache#driver-prerequisites
     *
     * @example
     *  'cache' => 'redis'
     */
    'lock' => [
        'cache' => null,
        'enabled' => false,
        'seconds' => 1,
    ],

    /**
     * Sometimes a slug may not match the currency and you need the ability to add an exception.
     * The main thing is that there are not many exceptions).
     *
     * Syntax:
     *  'slug' => 'currency'
     *
     * @example
     *  'my-usd' => 'USD'
     */
    'currencies' => [],

    /**
     * Services are the main core of the library and sometimes they need to be improved.
     * This configuration will help you to quickly customize the library.
     */
    // 'services' => [
    //     'exchange' => ExchangeService::class,
    //     'common' => CommonService::class,
    //     'wallet' => WalletService::class,
    //     'lock' => LockService::class,
    // ],

    // 'objects' => [
    //     'bring' => Bring::class,
    //     'cart' => Cart::class,
    //     'emptyLock' => EmptyLock::class,
    //     'operation' => Operation::class,
    // ],

    /**
     * Transaction model configuration.
     */
    'transaction' => [
        'table' => 'transactions',
        'model' => Transaction::class,
        'casts' => [
            'amount' => $bcLoaded ? 'string' : 'int',
        ],
    ],

    /**
     * Transfer model configuration.
     */
    'transfer' => [
        'table' => 'transfers',
        'model' => Transfer::class,
        'casts' => [
            'fee' => $bcLoaded ? 'string' : 'int',
        ],
        'storefront' => true
    ],

    /**
     * Wallet model configuration.
     */
    'wallet' => [
        'table' => 'wallets',
        'model' => Wallet::class,
        'casts' => [
            'balance' => $bcLoaded ? 'string' : 'int',
        ],
        'default' => [
            'name' => 'Default Wallet',
            'slug' => 'default',
        ],
    ],

    'report' => [
        // Default reporting time in days
        'default' => 7,
        'take' => 10,
    ]

];
