<?php

namespace App\Models;

use Altwaireb\World\Models\Country as Model;
use Illuminate\Database\Eloquent\Builder;

class Country extends Model
{
    protected $guarded = [];
    /**
     * Scope para filtrar solo los países activos
     */
    public function scopeActivos( $query)
    {
        return $query->where('is_active', true);
    }
}

