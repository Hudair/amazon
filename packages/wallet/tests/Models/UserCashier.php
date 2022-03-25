<?php

namespace Incevio\Package\Wallet\Test\Models;

use Incevio\Package\Wallet\Traits\HasWallets;
use Incevio\Package\Wallet\Traits\MorphOneWallet;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 */
class UserCashier extends Model
{
    use Billable, HasWallets, MorphOneWallet;

    /**
     * @return string
     */
    public function getTable(): string
    {
        return 'users';
    }
}
