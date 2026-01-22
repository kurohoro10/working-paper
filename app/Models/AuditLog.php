<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AuditLog
 *
 * Stores immutable system actions.
 */
class AuditLog extends Model
{
    protected $fillable = [
        'action',
        'metadata',
        'user_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function auditable()
    {
        return $this->morphTo();
    }
}
