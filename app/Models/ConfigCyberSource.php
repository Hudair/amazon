<?php

namespace App\Models;

class ConfigCyberSource extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'config_cybersources';

    /**
     * The database primary key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'shop_id';

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'sandbox' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'merchant_id',
        'api_key_id',
        'secret',
        'sandbox',
    ];

    /**
     * Setters.
     */
    public function setMerchantIdAttribute($value)
    {
        $this->attributes['merchant_id'] = trim($value);
    }

    public function setApiKeyIdAttribute($value)
    {
        $this->attributes['api_key_id'] = trim($value);
    }

    public function setSecretAttribute($value)
    {
        $this->attributes['secret'] = trim($value);
    }

    public function setSandboxAttribute($value)
    {
        $this->attributes['sandbox'] = (bool) $value;
    }
}
