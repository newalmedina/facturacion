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

    public function getTaxesAmountAttribute()
    {
        if ($this->price !== null && $this->taxes !== null) {
            $taxRate = $this->taxes / 100;  // Convert tax percentage to decimal
            $taxAmount = $this->price * $taxRate;  // Calculate the tax amount
            return $taxAmount;  // Devolver el valor como float, sin formatear
        }
        return 0.0;  // Return 0.0 if no price or taxes are set
    }

    public function getTotalPriceAttribute()
    {
        if ($this->price !== null) {
            $taxAmount = $this->taxes_amount ?? 0.0;  // Ensure it's a float
            $totalPrice = $this->price + $taxAmount;  // Add tax amount to base price
            return $totalPrice;  // Return the total price as a float
        }
        return 0.0;  // Return 0.0 if price is not set
    }
}
