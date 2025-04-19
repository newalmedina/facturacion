<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    // Guarding fields from mass-assignment
    protected $guarded = [];

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
