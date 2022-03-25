<?php

namespace Incevio\Package\Wallet\Test\Models;

use Incevio\Package\Wallet\Interfaces\Confirmable;
use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Traits\CanConfirm;
use Incevio\Package\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserConfirm.
 *
 * @property string $name
 * @property string $email
 */
class UserConfirm extends Model implements Wallet, Confirmable
{
    use HasWallet, CanConfirm;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email'];

    /**
     * @return string
     */
    public function getTable(): string
    {
        return 'users';
    }
}
