<?php

namespace App\Models;

use Altwaireb\World\Models\State as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class State extends Model
{

    protected $guarded = [];
     /**
     * Relación con el modelo Country
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
