<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\Setting;

class ReceiptService
{
    public function generate(Order $order)
    {
        // Obtener settings y mapearlos a array plano
        $settings = Setting::first();
        $generalSettings = $settings?->general;

        // Cargar relaciones
        $order->load('orderDetails', 'customer');

        // Generar PDF
        return Pdf::loadView('pdf.factura', [
            'order' => $order,
            'generalSettings' => $generalSettings
        ])->setPaper('A4', 'portrait');
    }
}
