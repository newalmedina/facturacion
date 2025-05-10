<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
