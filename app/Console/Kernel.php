<?php

namespace App\Console;

use App\Models\SystemConfig;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('activitylog:clean')->daily(); //Clean older activity logs
        // $schedule->command('incevio:kpi')->dailyAt('23:58');
        // $schedule->command('backup:clean')->daily()->at('01:00');
        // $schedule->command('backup:run')->daily()->at('02:00');
        // $schedule->command('backup:monitor')->daily()->at('03:00');

        // Generate sitemap command
        $this->sitemapCommand($schedule);

        // Keep the queue worker alive
        $schedule->command('worker:weakup')->everyFifteenMinutes()
            ->runInBackground()->withoutOverlapping();

        // Check local subscription expiry and charge
        if (is_subscription_enabled() && SystemConfig::isBillingThroughWallet()) {
            // $time = (int) config('subscription.default.charge_min_before_expiry') - 1;
            $schedule->command('subscription:recheck')->hourly();
        }

        // Reset demo content for demo hosting
        if (config('app.demo') == true) {
            $schedule->command('incevio:reset-demo')->twiceDaily(1, 13); //Reset the demo applcoation
        }

        // Re-evaluate ratings for models
        $schedule->command('incevio:evaluate-ratings')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Register the Generate sitemap commands for the sitemap.xml.
     *
     * @return void
     */
    private function sitemapCommand(Schedule $schedule)
    {
        $interval = in_array(config('seo.sitemap.update'), ['hourly', 'daily', 'weekly', 'monthly', 'yearly']) ?
            config('seo.sitemap.update') : 'daily';

        $schedule->command('seo:generate-sitemap')->$interval();
    }
}
