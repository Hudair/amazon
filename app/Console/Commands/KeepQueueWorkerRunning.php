<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KeepQueueWorkerRunning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'worker:weakup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure that the queue worker is running.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->isQueueListenerRunning()) {
            Log::info('Queue listener not running');
            $this->comment('Queue listener is being started.');
            $pid = $this->startQueueListener();
            $this->saveQueueListenerPID($pid);
        }

        $this->comment('Queue listener is running.');
    }

    /**
     * Check if the queue listener is running.
     *
     * @return bool
     */
    private function isQueueListenerRunning()
    {
        if (!$pid = $this->getLastQueueListenerPID()) {
            return false;
        }

        $process = exec("ps -p $pid -opid=,cmd=");
        $processIsQueueListener = !empty($process);

        return $processIsQueueListener;
    }

    /**
     * Get any existing queue listener PID.
     *
     * @return bool|string
     */
    private function getLastQueueListenerPID()
    {
        if (!file_exists(storage_path('queue.pid'))) {
            return false;
        }

        return file_get_contents(storage_path('queue.pid'));
    }

    /**
     * Save the queue listener PID to a file.
     *
     * @param $pid
     *
     * @return void
     */
    private function saveQueueListenerPID($pid)
    {
        file_put_contents(storage_path('queue.pid'), $pid);
    }

    /**
     * Start the queue listener.
     *
     * @return int
     */
    private function startQueueListener()
    {
        Log::info('Called startQueueListener!');

        $command = 'php -d register_argc_argv=On ' . base_path() . '/artisan queue:work --timeout=60 --sleep=5 --tries=3 > /dev/null & echo $!';

        $pid = exec($command);

        return $pid;
    }
}
