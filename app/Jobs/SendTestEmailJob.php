<?php

// app/Jobs/SendCronTestEmailJob.php
namespace App\Jobs;

use App\Mail\CronTestEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;  // <- Importar Log
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCronTestEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new CronTestEmail());

        Log::info("Correo automático enviado a {$this->email} para probar que el cron funciona perfectamente.");
    }
}
