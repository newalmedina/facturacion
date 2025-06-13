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
        $settings = Setting::pluck('value', 'key')->map(function ($value) {
            return is_array($value) ? $value : json_decode($value, true);
        });

        $generalSettings = collect($settings)->filter(function ($_, $key) {
            return str_starts_with($key, 'general.');
        })->mapWithKeys(function ($value, $key) {
            return [str_replace('general.', '', $key) => $value];
        })->toArray();

        // Cargar relaciones
        $order->load('orderDetails', 'customer');

        // Generar PDF
        return Pdf::loadView('pdf.ticket', [
            'order' => $order,
            'settings' => $generalSettings
        ])->setPaper([0, 0, 220, 800]); // ~58mm
    }
}
