<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AppointmentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $query;

    // El constructor acepta la consulta (query builder)
    public function __construct($query)
    {
        $this->query = $query;
    }

    // Ejecutar la consulta y devolver la colección
    public function collection()
    {
        return $this->query->get();
    }

    // Mapear cada fila para exportar los campos deseados
    public function map($item): array
    {
        return [
            $item->worker ? $item->worker->name : '', // Nombre del trabajador
            $item->date ? $item->date->format('d-m-Y') : '', // Fecha formateada
            $item->start_time ? date('H:i', strtotime($item->start_time)) : '', // Hora de inicio sin segundos
            $item->end_time ? date('H:i', strtotime($item->end_time)) : '', // Hora de fin sin segundos
            $this->getStatusLabel($item->status), // Estado con etiqueta legible
            $item->requester_email,
            $item->requester_phone,
            $item->comments,
        ];
    }

    // Encabezados para el archivo exportado
    public function headings(): array
    {
        return [
            'Trabajador',
            'Fecha',
            'Hora de inicio',
            'Hora de fin',
            'Estado',
            'Correo del solicitante',
            'Teléfono del solicitante',
            'Comentarios',
        ];
    }

    // Método para traducir el estado a texto legible
    protected function getStatusLabel(?string $status): string
    {
        return match ($status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmado',
            //'accepted' => 'Aceptada',
            'cancelled' => 'Cancelada',
            default => 'Sin estado',
        };
    }
}
