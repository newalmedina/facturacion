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

        $settings = Setting::first();
        if ($settings && $settings->general) {
            $generalSettings = $settings->general;

            if (!empty($generalSettings->image)) {
                $this->brandLogoUrl = Storage::url(str_replace('"', '', $generalSettings->image));
            }
            $this->brandName = !empty($generalSettings->brand_name)
                ? str_replace('"', '', $generalSettings->brand_name)
                : config('app.name');
        }
    }

    public function build()
    {
        return $this->subject('Orden eliminada')
            ->view('emails.order_deleted')
            ->with([
                'brandLogoUrl' => $this->brandLogoUrl,
                'brandName' => $this->brandName,
            ]);
    }
}
