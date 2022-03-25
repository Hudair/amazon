<?php

namespace App\Events\Profile;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdated
{
    use Dispatchable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @param  user  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
