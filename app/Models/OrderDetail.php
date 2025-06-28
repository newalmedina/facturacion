<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'item_id',
        'original_price',
        'price',
        'taxes',
        'quantity',
    ];

    protected $appends = [
        'total_base_price',
        'taxes_amount',
        'total_with_taxes'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getProductNameFormattedAttribute(): string
    {
        return $this->item_id && $this->item
            ? $this->item->name
            : ($this->product_name ?? 'Sin nombre');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getTotalBasePriceAttribute(): float
    {
        return round($this->price * $this->quantity, 2);
    }
    public function getTaxesAmountAttribute(): float
    {
        $price = round((float) $this->price, 2);
        $taxes = round((float) $this->taxes, 2);

        return round(($price * $taxes * $this->quantity) / 100, 2);
    }
    public function getTotalWithTaxesAttribute(): float
    {
        return round($this->total_base_price + $this->taxes_amount, 2);
    }
}
