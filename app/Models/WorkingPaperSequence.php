<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkingPaperSequence
 *
 * Maintains yearly counters for working paper job references.
 */
class WorkingPaperSequence extends Model
{
    protected $fillable = ['year', 'last_number'];
}
