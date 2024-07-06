<?php

namespace App\Console;

use App\Console\Commands\InterventionsInvoices;
use App\Console\Commands\ReservationsInvoices;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\GenerateRecurringClosures::class,
        ReservationsInvoices::class,
        InterventionsInvoices::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command(ReservationsInvoices::class)->monthlyOn('1','15:00');
        $schedule->command(InterventionsInvoices::class)->monthlyOn('1','15:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
