<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class AppointmentTemplate extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'active',
        'is_general',
        'worker_id',
    ];
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->center_id = Auth::user()->center_id;
        });
    }
    public function scopeMyCenter($query)
    {
        return $query->where('appointment_templates.center_id', Auth::user()->center_id);
    }

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

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
