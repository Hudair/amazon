<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable, SerializesModels;

    public $user;
    public $admin;
    public $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $admin, $password)
    {
        $this->user = $user;
        $this->admin = $admin;
        $this->password = $password;
    }
}
