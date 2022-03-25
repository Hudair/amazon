<?php

namespace Incevio\Package\Wallet\Test\Models;

use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 */
class User extends Model implements Wallet
{
    use HasWallet;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email'];
}
