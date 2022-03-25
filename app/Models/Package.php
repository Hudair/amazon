<?php

namespace App\Models;

use App\Models\System;

class Package extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'version',
        'compatible',
        'dependency',
        'active',
        'license_key',
        'installation_key',
        'installation_hash',
        'lcd',
        'lrd',
    ];

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Check it the package is compatible with the current zCart version.
     *
     * @var bool
     */
    public function isCompatible()
    {
        return version_compare($this->compatible, System::VERSION);
    }

    /**
     * Deactivate the package
     *
     * @var void
     */
    public function deactivate()
    {
        $this->active = false;
        $this->save();
    }
}
