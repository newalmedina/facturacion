<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CronTestEmail;

class SendCronTestEmailCommand extends Command
{
    // Nombre para ejecutar el comando desde consola
    protected $signature = 'email:send-cron-test';

    // Descripción breve del comando
    protected $description = 'Envía un correo para probar que el cron funciona correctamente';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $email = 'ing.newal.medina@gmail.com';

        Mail::to($email)->send(new CronTestEmail());

        Log::info("Correo automático enviado a {$email} para probar que el cron funciona perfectamente.");

        $this->info("Correo enviado correctamente a {$email}");
    }
}
