<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
    private static function generateCode($order)
    {
        if ($order->type == 'sale') {
            $prefix = 'VEN';
            $datePart = Carbon::now()->format('ymd');

            $latest = self::where('type', 'sale')
                ->whereDate('created_at', Carbon::today())
                ->whereNotNull("code")
                ->orderBy('id', 'desc')
                ->first();

            $lastCode = $latest?->code;

            if ($lastCode && Str::startsWith($lastCode, $prefix . $datePart)) {
                $lastSequence = (int)substr($lastCode, -3);
                $nextSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $nextSequence = '001';
            }

            return $prefix . $datePart . $nextSequence;
        }
        return null;
    }

    protected static function booted(): void
    {
        // Asigna automáticamente el usuario autenticado al crear
        static::creating(function ($order) {
            if (Auth::check()) {
                $order->created_by = Auth::id();
                $order->code = self::generateCode($order);
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
