<?php

namespace Incevio\Package\Wallet\Test\Models;

use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Interfaces\WalletFloat;
use Incevio\Package\Wallet\Traits\HasWalletFloat;
use Incevio\Package\Wallet\Traits\HasWallets;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 */
class UserMulti extends Model implements Wallet, WalletFloat
{
    use HasWalletFloat, HasWallets;

    /**
     * @return string
     */
    public function getTable(): string
    {
        return 'users';
    }
}
