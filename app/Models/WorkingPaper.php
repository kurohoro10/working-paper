<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WorkingPaper extends Model
{
    use SoftDeletes;

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
            $wp->share_token = Str::uuid();
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

        $lastRecord = self::withTrashed()
            ->where('job_reference', 'LIKE', "{$prefix}%")
            ->orderBy('job_reference', 'desc')
            ->first();

        $nextNumber = $lastRecord ? ((int) substr($lastRecord->job_reference, -4)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }


}
