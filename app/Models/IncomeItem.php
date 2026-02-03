<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeItem extends Model
{
    protected $fillable = [
        'working_paper_id',
        'rental_property_id',
        'work_type',
        'description',
        'amount',
        'quarter',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function workingPaper()
    {
        return $this->belongsTo(WorkingPaper::class);
    }

    public function rentalProperty()
    {
        return $this->belongsTo(RentalProperty::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
