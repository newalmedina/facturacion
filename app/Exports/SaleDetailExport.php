<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleDetailExport implements FromCollection, WithHeadings, WithMapping
{
    protected $query;

    // El constructor ahora acepta una consulta (query builder)
    public function __construct($query)
    {
        $this->query = $query;
    }

    // Ejecutar la consulta en el método collection y devolver la colección de resultados
    public function collection()
    {
        return $this->query->get();  // Ejecución de la consulta
    }

    // Mapear cada fila de la colección para modificar los datos
    public function map($item): array
    {
        return [
            $item->order->code ?? null,  // Código de la orden
            $item->order->date ? Carbon::parse($item->order->date)->format('d-m-Y') : null, // Fecha orden
            ucfirst($item->order->status == "pending" ? "Pendiente" : "Facturado"),
            $item->order->customer->name ?? null, // Cliente de la orden
            $item->getProductNameFormattedAttribute(), // Nombre producto formateado
            // $item->original_price,
            $item->price,
            // $item->taxes,
            $item->quantity,
            $item->total_base_price,    // Calculado (price * quantity)
            // $item->taxes_amount,        // Calculado
            // $item->total_with_taxes,    // Calculado
        ];
    }


    // Definir los encabezados que aparecerán en el archivo exportado
    public function headings(): array
    {
        return [
            'Código Orden',
            'Fecha Orden',
            'Estado',
            'Cliente',
            'Producto',
            // 'Precio Original',
            'Precio',
            //'Impuestos %',
            'Cantidad',
            'Subtotal',
            // 'Impuestos',
            // 'Total con Impuestos',
        ];
    }
}
