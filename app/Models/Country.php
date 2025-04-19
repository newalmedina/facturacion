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
    public function scopeActivos($query)
    {
        return $query->where('is_active', true);
    }
    public function users()
    {
        return $this->hasMany(User::class);  // A category can have many items
    }
    public function supliers()
    {
        return $this->hasMany(Supplier::class);  // A category can have many items
    }
    public function countries()
    {
        return $this->hasMany(Country::class);  // A category can have many items
    }

    public function citiesList()
    {
        return $this->hasMany(City::class);  // A category can have many items
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);  // A category can have many items
    }
    public function statesList()
    {
        return $this->hasMany(State::class);  // A category can have many items
    }

    public function getCanDeleteAttribute(): bool
    {
        return $this->users()->doesntExist()
            && $this->countries()->doesntExist()
            && $this->custommers()->doesntExist()
            && $this->supliers()->doesntExist()
            && $this->statesList()->doesntExist()
            && $this->citiesList()->doesntExist();
    }
}
