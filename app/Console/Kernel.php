<?php

namespace App\Console;

use App\Console\Commands\CheckVendor;
use App\Console\Commands\PayoutCryptoCurrencyUpdateCron;
use App\Console\Commands\RenewPlans;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


    protected $commands = [
        PayoutCryptoCurrencyUpdateCron::class,
        CheckVendor::class,
        RenewPlans::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $basicControl = basicControl();
        if ($basicControl->currency_layer_auto_update == 1) {
            $schedule->command('app:gateway-currency-update')->{basicControl()->currency_layer_auto_update_at}();
            $schedule->command('payout-currency:update')->{basicControl()->currency_layer_auto_update_at}();
        }
        $schedule->command('renew:plans')->daily();
        $schedule->command('app:check-vendor')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
