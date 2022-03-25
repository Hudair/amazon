<?php

namespace Incevio\Package\Wallet\Test\Models;

use Incevio\Package\Wallet\Interfaces\Customer;
use Incevio\Package\Wallet\Traits\CanPay;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 */
class Buyer extends Model implements Customer
{
    use CanPay;

    /**
     * @return string
     */
    public function getTable(): string
    {
        return 'users';
    }
}
