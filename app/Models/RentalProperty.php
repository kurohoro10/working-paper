<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalProperty extends Model
{
    protected $fillable = [
        'working_paper_id',
        'address_label',
        'ownership_percentage',
        'rented_from',
        'rented_to',
    ];

    protected $casts = [
        'ownership_percentage' => 'decimal:2',
        'rented_from' => 'date',
        'rented_to' => 'date',
    ];

    public function workingPaper()
    {
        return $this->belongsTo(WorkingPaper::class);
    }

    public function expenseItems()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    public function incomeItems()
    {
        return $this->hasMany(IncomeItem::class);
    }
}
