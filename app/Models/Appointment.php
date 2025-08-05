<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Guarding fields from mass-assignment
    protected $guarded = [];

    protected $casts = [
        'date' => 'date', // o 'datetime' si tiene tiempo
        'start_time' => 'datetime:H:i', // para que sea una instancia Carbon con formato hora
        'end_time' => 'datetime:H:i',
    ];

    // The worker assigned to the appointment
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
    public function canDelete(): bool
    {
        return !is_null($this->status) && $this->status !== 'cancelled';
    }
    // Template de la cita
    public function template()
    {
        return $this->belongsTo(AppointmentTemplate::class, 'template_id');
    }
}
