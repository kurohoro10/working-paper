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
        'meta',
        'user_id',
        'auditable_id',
        'auditable_type',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * User who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic target of the audit log.
     */
    public function auditable()
    {
        return $this->morphTo();
    }
}
