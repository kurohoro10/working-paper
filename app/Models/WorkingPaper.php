<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingPaper extends Model
{
    protected $fillable = [
        'user_id',
        'client_name',
        'service',
        'period',
        'status',
        'finalised_at',
        'snapshot_pdf_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /**
     * Boot method to auto-generate job_reference
     */
    protected static function booted()
    {
        static::creating(function ($wp) {
            if (!$wp->job_reference) {
                $wp->job_reference = self::generateJobReference();
            }
        });
    }

    /**
     * Generate a unique job reference.
     *
     * Format: WP-YYYY-XXXX
     */
    public static function generateJobReference()
    {
        $year = date('Y');
        $prefix = "WP-{$year}-";

        $lastRecord = self::where('job_reference', 'LIKE', "{$prefix}%")
            ->orderBy('job_reference', 'desc')
            ->first();

        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->job_reference, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
