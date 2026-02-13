<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OtherExpenseItem extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function details()
    {
        return $this->hasMany(OtherExpenseDetail::class);
    }
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->center_id = Auth::user()->center_id;
        });
    }


    public function scopeMyCenter($query)
    {
        return $query->where('center_id', Auth::user()->center_id);
    }


    public function getCanDeleteAttribute(): bool
    {
        return $this->details()->doesntExist();
    }
    /**
     * Scope a query to only include active items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
