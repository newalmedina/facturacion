<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherExpense extends Model
{
    use HasFactory;

    protected $guarded = []; 
    
    public function details()
    {
        return $this->hasMany(OtherExpenseDetail::class);
    }
    /**
     * Obtener el total (suma del precio de los detalles)
     */
    public function getTotalAttribute()
    {
        // Sumar los precios de todos los detalles relacionados
        return $this->details()->sum('price');
    }

     /**
     * Obtener el nombre de los items como un string separado por comas
     */
    public function getItemnamestringAttribute()
    {
        // Obtener los nombres de los items relacionados y unirlos con coma
        return $this->details->map(function ($detail) {
            return $detail->item->name; // Obtener el nombre del item desde la relación
        })->implode(', '); // Unir los nombres con coma
    }
}
