<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Brand extends Model
{
    use HasFactory;

    // Guarding fields from mass-assignment
    protected $guarded = [];
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->center_id = Auth::user()->center_id;
        });
    }
    // Additional methods or relationships can go here
    public function items()
    {
        return $this->hasMany(Item::class);  // A category can have many items
    }
    public function getCanDeleteAttribute(): bool
    {
        return $this->items()->doesntExist();
    }
}
