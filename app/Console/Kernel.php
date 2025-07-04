<?php

namespace App\Console;

use App\Console\Commands\HelloCron;
use App\Jobs\SendCronTestEmailJob;
use App\Jobs\SendTestEmailJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->job(new SendTestEmailJob)->everyMinute();
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            // Email al que quieres enviar la prueba
            SendCronTestEmailJob::dispatch('correo@ejemplo.com');
        })->dailyAt('10:50')
            ->appendOutputTo(storage_path('logs/cron.log'));

        $schedule->command(HelloCron::class, ['--no-ansi'])
            ->everyMinute()
            ->appendOutputTo(storage_path('logs/cron.log'));
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
