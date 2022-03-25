<?php

namespace Incevio\Package\Wallet\Test\Models;

use Incevio\Package\Wallet\Interfaces\Wallet;
use Incevio\Package\Wallet\Interfaces\WalletFloat;
use Incevio\Package\Wallet\Traits\HasWalletFloat;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserFloat.
 *
 * @property string $name
 * @property string $email
 */
class UserFloat extends Model implements Wallet, WalletFloat
{
    use HasWalletFloat;

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
