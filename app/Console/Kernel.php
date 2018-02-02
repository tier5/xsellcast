<?php

namespace App\Console;

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
        Commands\Inspire::class,
        \App\Console\Commands\OntraportCustomerSync::class,
        \App\Console\Commands\OntraportSalesRepSync::class,
        \App\Console\Commands\OntraportOfferCustomerSync::class,
        \App\Console\Commands\AppDeploy::class,
        \App\Console\Commands\OntraportCustomerRequestSync::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->exec('sync:ontraport-request')->everyMinute();
        
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
