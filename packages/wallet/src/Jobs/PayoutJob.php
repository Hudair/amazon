<?php

namespace Incevio\Package\Wallet\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Incevio\Package\Wallet\Models\Transaction;

class PayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $transaction;

    protected $notifiable;

    protected $tries = 5;

    protected $timeout = 20;

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct(Transaction $transaction, $notifiable)
    {
        $this->notifiable = $notifiable;
        $this->transaction = $transaction;
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        if ($this->transaction->payable) {
            // Send notifications to all active channels
            $this->transaction->payable->notify(new $this->notifiable($this->transaction));
        }
    }
}
