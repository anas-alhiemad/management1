<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    /*
// dailyAt('12:00')
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            \App\Http\Controllers\ItemController::checkExpiredItems();
        })->dailyAt('12:00');
    }
        */
// everyMinute
/*
protected function schedule(Schedule $schedule): void
{
    $schedule->call(function () {
        \App\Http\Controllers\ItemController::checkExpiredItems();
    })->everyMinute();
}
    */
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
