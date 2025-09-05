<?php

namespace App\Models;

use Carbon\Carbon;
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
    protected $appends = ['start_date', 'end_date'];
    // The worker assigned to the appointment
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function canDelete(): bool
    {
        return !is_null($this->status) && $this->status !== 'cancelled';
    }
    // Order.php

    public function appointment()
    {
        return $this->belongsTo(App\Models\Appointment::class, 'appointment_id');
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
            'pending_confirmation' => 'Pendiente Confirmación',
            'cancelled' => 'Cancelada',
            // null => 'Sin estado',
            // '' => 'Sin estado',
        ];

        return $labels[$this->status] ?? ucfirst($this->status ?? 'Sin estado');
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'available' => '#6c757d',   // gris (bootstrap secondary)
            'confirmed' => '#28a745', // verde (bootstrap success)
            'cancelled' => '#dc3545', // rojo (bootstrap danger)
            'pending_confirmation' => '#63b2f7ff',          // gris
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
        static::saving(function ($appointment) {
            // Si alguno es null o vacío, duration en 0
            if (empty($appointment->start_time) || empty($appointment->end_time)) {
                $appointment->duration_minutes = 0;
            } else {
                // start_time y end_time son casted a Carbon (datetime)
                $start = $appointment->start_time;
                $end = $appointment->end_time;

                // Calcular diferencia en minutos
                $appointment->duration_minutes = $end->diffInMinutes($start);
            }
        });
    }
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Scope para items con estado pendiente (o cualquier status pasado como parámetro)
    public function scopeStatusAvailable($query)
    {
        return $query->where('status', 'available');
    }
    public function scopeStatusConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }


    public function getStartDateAttribute()
    {
        if ($this->date && $this->start_time) {
            // Tomamos solo la fecha de $this->date
            $fecha = $this->date->format('Y-m-d');
            // Tomamos solo la hora de $this->start_time
            $hora  = $this->start_time->format('H:i:s');

            return Carbon::parse($fecha . ' ' . $hora);
        }
        return null;
    }

    public function getEndDateAttribute()
    {
        if ($this->date && $this->end_time) {
            $fecha = $this->date->format('Y-m-d');
            $hora  = $this->end_time->format('H:i:s');

            return Carbon::parse($fecha . ' ' . $hora);
        }
        return null;
    }
}
