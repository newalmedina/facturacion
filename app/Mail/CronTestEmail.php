<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CronTestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;

    public function __construct()
    {
        $this->userName = 'Newal Medina';
    }

    public function build()
    {
        return $this->markdown('emails.cron_test_email')
            ->subject('Validando funcioanmiento del cron')
            ->with('userName', $this->userName);
    }
}
