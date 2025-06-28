<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Correo de Prueba')
                    ->html('<p>Este es un correo de prueba enviado automáticamente cada minuto.</p>');
    }
}
