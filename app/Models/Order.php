<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $appends = [
        'subtotal',
        'impuestos',
        'total',
        'products', // <-- aquí lo agregas
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getProductsAttribute(): string
    {
        return $this->orderDetails
            ->map(fn($detail) => $detail->product_name_formatted)
            ->implode(', ');
    }


    public function getDisabledSalesAttribute(): bool
    {
        return $this->status == 'invoiced';
    }
    private static function generateCode($order)
    {
        if ($order->type !== 'sale') {
            return null;
        }

        $prefix = 'VEN';
        $datePart = Carbon::now()->format('ymd');

        // Obtener el último código creado (incluyendo soft deleted)
        $latest = self::withTrashed()
            ->where('type', 'sale')
            ->whereDate('created_at', Carbon::today())
            ->whereNotNull('code')
            ->orderBy('id', 'desc')
            ->first();

        if ($latest && Str::startsWith($latest->code, $prefix . $datePart)) {
            // Extraer la secuencia numérica del código (últimos 3 dígitos)
            $lastSequence = (int) substr($latest->code, -3);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        // Buscar un código que no exista (incluyendo soft deleted)
        do {
            $code = $prefix . $datePart . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            $exists = self::withTrashed()
                ->where('code', $code)
                ->exists();

            if ($exists) {
                $nextSequence++;
            } else {
                break;
            }
        } while (true);

        return $code;
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

    public function getSubtotalAttribute(): float
    {
        return round($this->orderDetails->sum('total_base_price'), 2);
    }

    public function getImpuestosAttribute(): float
    {
        return round($this->orderDetails->sum('taxes_amount'), 2);
    }

    public function getTotalAttribute(): float
    {
        return round($this->subtotal + $this->impuestos, 2);
    }
    public function scopeWithCalculatedTotals(Builder $query): Builder
    {
        return $query
            ->select('orders.*') // Muy importante para que no se sobrescriba la query base
            ->selectSub(
                'SELECT COALESCE(SUM(price * quantity), 0)
             FROM order_details
             WHERE order_details.order_id = orders.id',
                'subtotal'
            )
            ->selectSub(
                'SELECT COALESCE(SUM((price * taxes * quantity) / 100), 0)
             FROM order_details
             WHERE order_details.order_id = orders.id',
                'impuestos'
            )
            ->selectSub(
                'SELECT COALESCE(SUM((price * quantity) + ((price * taxes * quantity) / 100)), 0)
             FROM order_details
             WHERE order_details.order_id = orders.id',
                'total'
            );
    }

    public function scopeSales($query)
    {
        return $query->where('type', "sale");
    }
    public function scopeInvoiced($query)
    {
        return $query->where('status', "invoiced");
    }
    public function scopePending($query)
    {
        return $query->where('status', "pending");
    }
}
