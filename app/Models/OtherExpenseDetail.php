<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherExpenseDetail extends Model
{
    use HasFactory;

    protected $guarded = []; 

    public function expense()
    {
        return $this->belongsTo(OtherExpense::class, 'other_expense_id');
    }

    public function item()
    {
        return $this->belongsTo(OtherExpenseItem::class, 'other_expense_item_id');
    }
}
