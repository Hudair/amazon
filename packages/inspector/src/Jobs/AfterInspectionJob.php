<?php

namespace Incevio\Package\Inspector\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Incevio\Package\Inspector\Services\InspectorService;

class AfterInspectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The inspector service instance
     */
    public $inspector;

    protected $notifiable;

    protected $tries = 5;

    protected $timeout = 20;

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct(InspectorService $inspector, $notifiable)
    {
        $this->notifiable = $notifiable;
        $this->inspector = $inspector;
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        // If the content belongs to a vendor
        if ($this->inspector->model->shop_id) {
            // Send notifications to all active channels
            $this->inspector->model->shop->notify(new $this->notifiable($this->inspector));
        }

    }
}
