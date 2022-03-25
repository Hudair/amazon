<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BoostPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incevio:boost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the cached views, configs and routes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Clear cache
        $this->call('optimize:clear');

        // Cache again
        $this->call('optimize');

        $this->info('Optimization done: <info>✔</info>');
    }
}
