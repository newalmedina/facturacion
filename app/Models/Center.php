<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    /**
     * Campos protegidos contra asignación masiva.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relaciones
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
    public function getDisplayNameAttribute()
    {
        return "{$this->name} ({$this->nif})";
    }
    /**
     * Devuelve la dirección completa en un solo string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->address,
            $this->postal_code,
            $this->city?->name,
            $this->state?->name,
            $this->country?->name,
        ];

        return implode(', ', array_filter($parts));
    }
    public function getImageBase64Attribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        $imagePath = storage_path('app/public/' . $this->image);

        if (is_file($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . $imageData;
        }

        return null;
    }
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
