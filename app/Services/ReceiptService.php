<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class ReceiptService
{
    public function generate(Order $order, $factura = 1)
    {

        $user = Auth::user();

        $center = $user?->center;
        $generalSettings = $center;


        $order->load('orderDetails', 'customer');

        return Pdf::loadView('pdf.factura', [
            'order' => $order,
            'generalSettings' => $generalSettings,
            'factura' => $factura
        ])->setPaper('A4', 'portrait');
    }
}
