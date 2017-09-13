<?php

namespace App\Console;

use App\Console\Commands\AbilityAdd;
use App\Console\Commands\BossAdd;
use App\Console\Commands\FightCache;
use App\Console\Commands\LogCache;
use App\Console\Commands\RunBot;
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
        RunBot::class,
        LogCache::class,
        BossAdd::class,
        FightCache::class,
        AbilityAdd::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
