<?php

namespace App\Events\Dispute;

use App\Models\Dispute;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DisputeSolved
{
    use Dispatchable, SerializesModels;

    public $dispute;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Dispute $dispute)
    {
        $this->dispute = $dispute;
    }
}
