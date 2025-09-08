<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    public function getFullAddressAttribute()
    {
        $parts = [
            $this->address,
            $this->postal_code,
            optional($this->city)->name,
            optional(optional($this->city)->state)->name,
            optional($this->country)->name,
        ];

        // Filtramos nulos/vacíos y unimos con coma
        return implode(', ', array_filter($parts));
    }
}
