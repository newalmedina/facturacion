<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'item_id',
        'quantity',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
