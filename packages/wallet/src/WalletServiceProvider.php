<?php

namespace Incevio\Package\Wallet;

use App\Common\PackageConfig;
use Illuminate\Support\ServiceProvider;
use Incevio\Package\Wallet\Commands\RefreshBalance;
use Incevio\Package\wallet\Repositories\Payouts\EloquentPayoutsRepository;
use Incevio\Package\wallet\Repositories\Payouts\PayoutsRepository;
use Incevio\Package\Wallet\Services\ExchangeService;
use Incevio\Package\Wallet\Services\CommonService;
use Incevio\Package\Wallet\Services\WalletService;
use Incevio\Package\Wallet\Services\LockService;
use Incevio\Package\Wallet\Interfaces\Rateable;
use Incevio\Package\Wallet\Interfaces\Storable;
use Incevio\Package\Wallet\Interfaces\Mathable;
use Incevio\Package\Wallet\Simple\Rate;
use Incevio\Package\Wallet\Simple\Store;
use Incevio\Package\Wallet\Simple\Math;

class WalletServiceProvider extends ServiceProvider
{
    use PackageConfig;

    /**
     * Bootstrap services.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/all.php');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'wallet');

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([RefreshBalance::class]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'wallet');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wallet');

        // Autoload helpers
        foreach(glob(__DIR__.'/Helpers/*.php') as $filename){
            require_once($filename);
        }

        // Bind eloquent models to IoC container
        $this->app->singleton(Rateable::class, Rate::class);
        $this->app->singleton(Storable::class, Store::class);
        $this->app->singleton(Mathable::class, Math::class);
        $this->app->singleton(ExchangeService::class, ExchangeService::class);
        $this->app->singleton(CommonService::class, CommonService::class);
        $this->app->singleton(WalletService::class, WalletService::class);
        $this->app->singleton(LockService::class, LockService::class);
        $this->app->singleton(PayoutsRepository::class, EloquentPayoutsRepository::class);

        // models
        // $this->app->bind(\Incevio\Package\Wallet\Models\Transaction::class, \Incevio\Package\Wallet\Models\Transaction::class);
        // $this->app->bind(\Incevio\Package\Wallet\Models\Transfer::class, \Incevio\Package\Wallet\Models\Transfer::class);
        // $this->app->bind(\Incevio\Package\Wallet\Models\Wallet::class, \Incevio\Package\Wallet\Models\Wallet::class);

        // object's
        // $this->app->bind(\Incevio\Package\Wallet\Objects\Bring::class, \Incevio\Package\Wallet\Objects\Bring::class);
        // $this->app->bind(\Incevio\Package\Wallet\Objects\Cart::class, \Incevio\Package\Wallet\Objects\Cart::class);
        // $this->app->bind(\Incevio\Package\Wallet\Objects\EmptyLock::class, \Incevio\Package\Wallet\Objects\EmptyLock::class);
        // $this->app->bind(\Incevio\Package\Wallet\Objects\Operation::class, \Incevio\Package\Wallet\Objects\Operation::class);
    }
}