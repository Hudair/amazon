<?php

namespace Incevio\Package\Wallet\Models;

use function app;
use function array_key_exists;
use function array_merge;
use Incevio\Package\Wallet\Interfaces\Confirmable;
use Incevio\Package\Wallet\Interfaces\Customer;
use Incevio\Package\Wallet\Interfaces\Exchangeable;
use Incevio\Package\Wallet\Interfaces\WalletFloat;
use Incevio\Package\Wallet\Services\WalletService;
use Incevio\Package\Wallet\Traits\CanConfirm;
use Incevio\Package\Wallet\Traits\CanExchange;
use Incevio\Package\Wallet\Traits\CanPayFloat;
use Incevio\Package\Wallet\Traits\HasGift;
use function config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

use Incevio\Package\Wallet\Models\Transaction as TransactionModel;

/**
 * Class Wallet.
 * @property string $holder_type
 * @property int $holder_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $balance
 * @property \Incevio\Package\Wallet\Interfaces\Wallet $holder
 * @property-read string $currency
 */
class Wallet extends Model implements Customer, WalletFloat, Confirmable, Exchangeable
{
    use CanConfirm;
    use CanExchange;
    use CanPayFloat;
    use HasGift;

    /**
     * @var array
     */
    protected $fillable = [
        'holder_type',
        'holder_id',
        'name',
        'slug',
        'description',
        'balance',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'blocked' => 'bool',
    ];

    /**
     * {@inheritdoc}
     */
    public function getCasts(): array
    {
        return array_merge(
            parent::getCasts(),
            config('wallet.wallet.casts', [])
        );
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        if (! $this->table) {
            $this->table = config('wallet.wallet.table', 'wallets');
        }

        return parent::getTable();
    }

    /**
     * @param string $name
     * @return void
     */
    public function setNameAttribute(string $name): void
    {
        $this->attributes['name'] = $name;

        /**
         * Must be updated only if the model does not exist
         *  or the slug is empty.
         */
        if (! $this->exists && ! array_key_exists('slug', $this->attributes)) {
            $this->attributes['slug'] = Str::slug($name);
        }
    }

    /**
     * @return bool
     */
    public function refreshBalance(): bool
    {
        return app(WalletService::class)->refresh($this);
    }

    /**
     * @return float|int
     */
    public function getAvailableBalance()
    {
        return $this->transactions()
            ->where('wallet_id', $this->getKey())
            ->where('confirmed', true)
            ->sum('amount');
    }

    /**
     * @return MorphTo
     */
    public function holder(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    // public function transactions(): HasMany
    // {
    //     return $this->hasMany(config('wallet.transaction.model', TransactionModel::class));
    // }


    /**
     * @return string
     */
    public function getCurrencyAttribute(): string
    {
        $currencies = config('wallet.currencies', []);

        return $currencies[$this->slug] ?? Str::upper($this->slug);
    }
}
