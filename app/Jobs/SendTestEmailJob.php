<?php

// app/Jobs/SendCronTestEmailJob.php
namespace App\Jobs;

use App\Mail\CronTestEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCronTestEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $email = 'el.solitions@gmail.com';

        Mail::to($email)->send(new CronTestEmail());

        Log::info("Correo automático enviado a {$email} para probar que el cron funciona perfectamente.");
    }
}
