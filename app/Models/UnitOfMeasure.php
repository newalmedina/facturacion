<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Optional: Define any relationships, if needed.
    // Inverse relationship with Items
    public function items()
    {
        return $this->hasMany(Item::class);  // A unit of measure can be used by many items
    }
    public function getCanDeleteAttribute(): bool
    {
        return  $this->items()->doesntExist();
    }
}
