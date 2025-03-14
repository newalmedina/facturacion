<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Optional: Define any relationships, if needed.
    // Inverse relationship with Items
    public function items()
    {
        return $this->hasMany(Item::class);  // A category can have many items
    }
}
