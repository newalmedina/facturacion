<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OtherExpenseExport implements FromCollection, WithHeadings, WithMapping
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
             $item->expense->date ? Carbon::parse( $item->expense->date)->format("d-m-Y"):null,  
             $item->expense->description,  
             $item->item->name,  
             $item->price,  
             $item->observations,  
           
         ];
     }
 
     // Definir los encabezados que aparecerán en el archivo exportado
     public function headings(): array
     {
         return [
             'Nombre',
             'Descripción',
             'Item',
             'Precio',
             'Observaciones',
             
         ];
     }
}


