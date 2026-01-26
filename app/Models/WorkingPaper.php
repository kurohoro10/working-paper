<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\WorkingPaperSequence;

class WorkingPaper extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'client_name',
        'service',
        'job_reference',
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
    public static function generateJobReference(): string
    {
        $year = now()->year;

        return DB::transaction(function () use ($year) {
            $sequence = WorkingPaperSequence::lockForUpdate()
                ->FirstOrCreate(
                    ['year'        => $year],
                    ['last_number' => 0]
                );

            $sequence->increment('last_number');

            return sprintf(
                'WP-%d-%04d',
                $year,
                $sequence->last_number
            );
        });
    }
}
