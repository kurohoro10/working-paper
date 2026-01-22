<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Represents a single expense line item.
 */
class Expense extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'description',
        'amount',
        'client_comment',
        'internal_comment',
        'receipt_path',
    ];

    public function workingPaper()
    {
        return $this->belongsTo(WorkingPaper::class);
    }

}
