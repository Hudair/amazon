<?php

namespace App\Console\Commands;

use Database\Seeders\demoSeeder;
use Illuminate\Console\Command;

class ImportDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incevio:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed demo data into the database';

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
        $this->call('cache:clear');

        $this->call('config:clear');

        $this->info('Seeding DEMO contents...');

        $this->call('db:seed', ['--force' => true, '--class' => demoSeeder::class]);

        // if (app()->runningInConsole() && (config('scout.driver') == 'mysql'))
        if (config('scout.driver') == 'mysql') {
            $this->call('scout:mysql-index');
        }

        $this->call('incevio:evaluate-ratings');

        $this->info('Demo data seeded!');

        \Log::info('DEMO DATA IMPORTED: <info>✔</info>');
    }
}
