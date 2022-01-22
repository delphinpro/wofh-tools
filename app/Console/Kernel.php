<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stat:load')
            ->everySixHours()
            ->runInBackground();
        // $schedule->command('stat:update', ['--limit' => '10'])
        //     ->hourlyAt(5)
        //     ->withoutOverlapping(120)
        //     // ->emailOutputOnFailure('delphinpro@tandex.ru')
        //     ->runInBackground();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require app_path('Console/helpers.php');
        require base_path('routes/console.php');
    }
}
