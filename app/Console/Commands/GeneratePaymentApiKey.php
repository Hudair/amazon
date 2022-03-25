<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GeneratePaymentApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incevio:generate-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate zcart_api_key and zcart_api_secret to deliver data securely over api';

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
     * @return int
     */
    public function handle()
    {
        /**
         * Generate api key, encryption key and iv
         */
        $data = [
            'ZCART_API_KEY' => Str::random(32),
            'ZCART_ENCRYPTION_KEY' => Str::random(32),
            'ZCART_ENCRYPTION_IV' => Str::random(16)
        ];

        $env = new \App\Services\EnvManager();
        foreach ($data as $k => $v) {
            $env->setValue($k, "\"{$v}\"", true);
        }
    }
}
