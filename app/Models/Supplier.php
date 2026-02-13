<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Supplier extends Model
{
    use HasFactory;


    // Use guarded to prevent mass-assignment for all fields except for the ones you explicitly want
    protected $guarded = [];
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->center_id = Auth::user()->center_id;
        });
    }

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
    public function items()
    {
        return $this->hasMany(Item::class);  // A category can have many items
    }
    public function getCanDeleteAttribute(): bool
    {
        return $this->items()->doesntExist();
    }
}
