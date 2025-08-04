<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
        'is_general',
    ];

    /**
     * Relación: una plantilla tiene muchos slots (horarios por día).
     */
    public function slots()
    {
        return $this->hasMany(AppointmentTemplateSlot::class);
    }
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
