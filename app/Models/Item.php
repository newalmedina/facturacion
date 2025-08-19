<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];  // Guarded to allow mass assignment

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);  // An item belongs to one category
    }

    // Relationship with Unit of Measure
    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class);  // An item belongs to one unit of measure
    }

    // Optional: Relationship with Brand (for products)
    public function brand()
    {
        return $this->belongsTo(Brand::class);  // An item belongs to one brand
    }

    // Optional: Relationship with Supplier (for products)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);  // An item belongs to one supplier
    }

    public function getTaxesAmountAttribute(): float
    {
        $price = round((float) $this->price, 2);
        $taxes = round((float) $this->taxes, 2);

        return round(($price * $taxes) / 100, 2);
    }



    public function getTotalPriceAttribute(): float
    {
        return round((float) $this->price + $this->taxes_amount, 2);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        $path = "/{$this->image}";

        // Verifica si el archivo existe en el disco 'public'
        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        // Retorna la URL pública del archivo
        return Storage::url($path);
    }


    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    // Scope para show_booking = true
    public function scopeShowBooking($query)
    {
        return $query->where('show_booking', true);
    }

    // Scope para show_booking_others = true
    public function scopeShowBookingOthers($query)
    {
        return $query->where('show_booking_others', true);
    }
}
