<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OrderDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $brandLogoUrl;
    public $brandName;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Orden eliminada')
            ->view('emails.order_deleted');
    }
}
