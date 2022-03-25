<?php

namespace App\Jobs;

use App\Models\Cancellation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AfterOrderCancellationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The inspector service instance
     */
    public $inspector;

    protected $tries = 5;

    protected $timeout = 20;

    public $cancellation;
    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cancellation $cancellation)
    {
        $this->cancellation = $cancellation;
        $this->order = $cancellation->order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->order) {
            // code...
        }
    }
}
