<?php

namespace App\Console;

use App\Models\System\SystemLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){

        SystemLog::addLog("start shadule",
            [date("Y-m-d H:i:s")],
            'start shadule'.date("Y-m-d H:i:s"),
            SystemLog::CODE_TASK
        );
        $schedule->command('cron:price_physical_to_day')
                 ->timezone("UTC")
                 ->dailyAt("23:00");

        $schedule->command('cron:price_digital_to_day')
                 ->timezone("Europe/London")
                 ->dailyAt("23:00");

        $schedule->command('cron:price_nasdaq_to_day')
                 ->timezone("America/New_York")
                 ->dailyAt("23:00");

        $schedule->command('import:price')
            ->timezone("America/New_York")
            ->everyFourHours();

      //  $schedule->command('site:math_cache')->dailyAt("00:01");
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(){
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
