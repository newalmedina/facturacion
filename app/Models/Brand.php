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
}
