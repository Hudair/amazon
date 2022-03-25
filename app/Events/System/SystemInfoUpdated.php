<?php

namespace App\Events\System;

use App\Models\System;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SystemInfoUpdated
{
    use Dispatchable, SerializesModels;

    public $system;

    /**
     * Create a new job instance.
     *
     * @param  System  $system
     * @return void
     */
    public function __construct(System $system)
    {
        // Clear system_settings from cache
        Cache::forget('system_settings');
        Cache::forget('system_timezone');
        Cache::forget('system_currency');

        $this->system = $system;
    }
}
