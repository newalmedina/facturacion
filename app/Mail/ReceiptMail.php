<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\PDF;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $order;

    /**
     * Create a new message instance.
     *
     * @param PDF $pdf
     * @param $order
     */
    public function __construct(PDF $pdf, $order)
    {
        $this->pdf = $pdf;
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject("Recibo para la orden {$this->order->code}")
            ->view('emails.receipt') // Puedes crear esta vista o usar texto plano
            ->attachData($this->pdf->output(), "{$this->order->code}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
