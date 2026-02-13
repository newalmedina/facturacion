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
    public $factura;

    /**
     * Create a new message instance.
     *
     * @param PDF $pdf
     * @param $order
     */
    public function __construct(PDF $pdf, $order, $factura = 1)
    {
        $this->pdf = $pdf;
        $this->order = $order;
        $this->factura = $factura;
    }

    public function build()
    {
        $nombre = "Factura  - {$this->order->code}";

        if (!$this->factura) {
            $nombre = "Presupuesto";
        }

        return $this->subject($nombre)
            ->view('emails.receipt') // Puedes crear esta vista o usar texto plano
            ->attachData($this->pdf->output(), "{$nombre}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
