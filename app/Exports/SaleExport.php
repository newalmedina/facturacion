<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleExport implements FromCollection, WithHeadings, WithMapping
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
            $item->code, // Código
            $item->date ? Carbon::parse($item->date)->format('d-m-Y') : null, // Fecha formateada
            $item->customer->name ?? null, // Cliente (relación)
            $item->products, // Producto (atributo calculado que concatena productos)
            $item->observations ?? null, // Observaciones (campo en tu modelo)
            $item->subtotal, // Subtotal (atributo calculado o columna selectSub)
            $item->impuestos, // Impuestos (atributo calculado o columna selectSub)
            $item->total, // Total (atributo calculado o columna selectSub)
            ucfirst($item->status == "pending" ? "Pendiente" : "Facturado"), // Estado (campo status con primera letra mayúscula)
        ];
    }


    // Definir los encabezados que aparecerán en el archivo exportado
    public function headings(): array
    {
        return [
            'Código',
            'Fecha',
            'Cliente',
            'Productos',
            'observaciones',
            'Subtotal',
            'Impuestos',
            'total',
            'estado',

        ];
    }
}
