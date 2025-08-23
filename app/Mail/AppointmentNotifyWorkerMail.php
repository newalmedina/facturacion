<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentNotifyWorkerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct($appointmentId)
    {
        $this->appointment = Appointment::find($appointmentId);
    }


    public function build()
    {
        return $this->subject("Nueva cita asignada")
            ->view('emails.appointment_notify_worker')
            ->with([
                'appointment' => $this->appointment
            ]);
    }
}
