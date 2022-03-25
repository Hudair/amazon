<?php

namespace Incevio\Package\Wallet\Commands;

use App\Services\DbService;
use Illuminate\Console\Command;
use Incevio\Package\Wallet\Models\Wallet;

/**
 * Class RefreshBalance.
 */
class RefreshBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculates all wallets';

    /**
     * @return void
     * @throws
     */
    public function handle(): void
    {
        DbService::transaction(static function () {
            Wallet::query()->each(static function (Wallet $wallet) {
                $wallet->refreshBalance();
            });
        });
    }
}
