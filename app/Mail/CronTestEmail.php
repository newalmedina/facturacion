<?php

// app/Mail/CronTestEmail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CronTestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Prueba de Funcionamiento del Cron')
            ->html('
                        <h2>Correo de Prueba - By-Estrella Salón Belleza</h2>
                        <p>Este es un correo de prueba para verificar que el cron funciona correctamente para la plataforma <strong>By-Estrella Salón Belleza</strong>.</p>
                        <p>¡Gracias por confiar en nosotros!</p>
                    ');
    }
}
