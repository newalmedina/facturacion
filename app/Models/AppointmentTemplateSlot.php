<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentTemplateSlot extends Model
{
    use HasFactory;

    // Guarding fields from mass-assignment
    protected $guarded = [];

    /**
     * Relación: un slot pertenece a una plantilla.
     */
    public function template()
    {
        return $this->belongsTo(AppointmentTemplate::class, 'appointment_template_id');
    }
}
