<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct($appointmentId)
    {
        $this->appointment = Appointment::find($appointmentId);
    }


    public function build()
    {
        return $this->subject("Acabas de solicitar una cita")
            ->view('emails.appointment_requested')
            ->with([
                'appointment' => $this->appointment
            ]);
    }
}
