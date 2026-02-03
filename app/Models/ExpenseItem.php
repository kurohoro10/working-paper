<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    protected $fillable = [
        'working_paper_id',
        'work_type',
        'rental_property_id',
        'type',
        'description',
        'amount_inc_gst',
        'gst_amount',
        'net_ex_gst',
        'quarter',
        'client_comment',
        'internal_comment',
    ];

    protected $casts = [
        'amount_inc_gst' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'net_ex_gst' => 'decimal:2',
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

    /**
     * Check if this expense has required attachments
     */
    public function hasAttachments(): bool
    {
        return $this->attachments()->exists();
    }
}
