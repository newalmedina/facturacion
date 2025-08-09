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

    public function getStatusNameFormattedAttribute(): string
    {
        $labels = [
            'available' => 'Disponible',
            'confirmed' => 'Confirmado',
            //'accepted' => 'Aceptada',
            'cancelled' => 'Cancelada',
            null => 'Sin estado',
            '' => 'Sin estado',
        ];

        return $labels[$this->status] ?? ucfirst($this->status ?? 'Sin estado');
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'available' => '#6c757d',   // gris (bootstrap secondary)
            'confirmed' => '#28a745', // verde (bootstrap success)
            'cancelled' => '#dc3545', // rojo (bootstrap danger)
            null => '#6c757d',        // gris
            '' => '#6c757d',          // gris
        ];

        return $colors[$this->status] ?? '#6c757d'; // gris por defecto
    }
    protected static function booted()
    {
        static::creating(function ($appointment) {
            if (empty($appointment->slug)) {
                $appointment->slug = \Illuminate\Support\Str::uuid()->toString();
            }
        });
    }
}
