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
    public function users()
    {
        return $this->hasMany(User::class);  // A category can have many items
    }
    public function supliers()
    {
        return $this->hasMany(Supplier::class);  // A category can have many items
    }
    public function citiesList()
    {
        return $this->hasMany(City::class);  // A category can have many items
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);  // A category can have many items
    }
    public function getCanDeleteAttribute(): bool
    {
        return $this->users()->doesntExist()
            && $this->supliers()->doesntExist()
            && $this->customers()->doesntExist()
            && $this->citiesList()->doesntExist();
    }
}
