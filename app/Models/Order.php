<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getDisabledSalesAttribute(): bool
    {
        return $this->status == 'invoiced';
    }

    protected static function booted(): void
    {
        // Asigna automáticamente el usuario autenticado al crear
        static::creating(function ($order) {
            if (Auth::check()) {
                $order->created_by = Auth::id();
            }
        });

        // Asigna automáticamente el usuario que elimina
        static::deleting(function ($order) {
            if (Auth::check() && !$order->isForceDeleting()) {
                $order->deleted_by = Auth::id();
                $order->save();
            }
        });
    }


    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
