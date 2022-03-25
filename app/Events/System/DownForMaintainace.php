<?php

namespace App\Events\System;

use App\Models\System;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DownForMaintainace
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
        $this->system = $system;
    }
}
