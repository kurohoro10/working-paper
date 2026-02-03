<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\WorkingPaperSequence;
use Illuminate\Validation\ValidationException;

class WorkingPaper extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'client_id',
        'service',
        'job_reference',
        'period',
        'status',
        'work_types', // ADDED
        'share_token',
        'share_token_expires_at',
        'finalised_at',
        'snapshot_pdf_path',
    ];

    protected $casts = [
        'share_token_expires_at' => 'datetime',
        'finalised_at' => 'datetime', // Good practice to add this too
        'work_types' => 'array', // ADDED - JSON column
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Legacy expenses relationship (if you still have the old expenses table)
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * New expense items for work types
     */
    public function expenseItems()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    /**
     * Income items for work types
     */
    public function incomeItems()
    {
        return $this->hasMany(IncomeItem::class);
    }

    /**
     * Rental properties
     */
    public function rentalProperties()
    {
        return $this->hasMany(RentalProperty::class);
    }

    /**
     * Audit logs
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    // ==========================================
    // SHARE TOKEN METHODS
    // ==========================================

    public function shareTokenIsExpired(): bool
    {
        // If there is no expiration date set, treat it as expired
        if (!$this->share_token_expires_at) {
            return true;
        }

        // Return true if the current time has passed the expiration date
        return now()->greaterThan($this->share_token_expires_at);
    }

    public function refreshShareToken(): self
    {
        $this->update([
            'share_token' => (string) Str::uuid(),
            'share_token_expires_at' => now()->addDays(1),
        ]);

        return $this;
    }

    // ==========================================
    // VALIDATION METHODS
    // ==========================================

    /**
     * Validate that all expense items have attachments before finalizing
     */
    public function validateAllExpensesHaveUploads(): void
    {
        $expensesWithoutUploads = $this->expenseItems() // FIXED: was expenseItems()
            ->whereDoesntHave('attachments') // FIXED: typo
            ->count();

        if ($expensesWithoutUploads > 0) {
            throw ValidationException::withMessages([
                'attachments' => "All expense items must have at least one attachment before finalizing."
            ]);
        }
    }

    // ==========================================
    // WORK TYPE HELPERS
    // ==========================================

    /**
     * Check if a specific work type is enabled
     */
    public function hasWorkType(string $type): bool
    {
        return in_array($type, $this->work_types ?? []);
    }

    /**
     * Get all enabled work types
     */
    public function getWorkTypes(): array
    {
        return $this->work_types ?? [];
    }

    /**
     * Add a work type
     */
    public function addWorkType(string $type): void
    {
        $workTypes = $this->work_types ?? [];

        if (!in_array($type, $workTypes)) {
            $workTypes[] = $type;
            $this->update(['work_types' => $workTypes]);
        }
    }

    /**
     * Remove a work type
     */
    public function removeWorkType(string $type): void
    {
        $workTypes = $this->work_types ?? [];
        $workTypes = array_values(array_diff($workTypes, [$type]));

        $this->update(['work_types' => $workTypes]);
    }

    // ==========================================
    // BOOT METHOD
    // ==========================================

    /**
     * Boot method to auto-generate job_reference and share token
     */
    protected static function booted()
    {
        static::creating(function ($wp) {
            if (!$wp->job_reference) {
                $wp->job_reference = self::generateJobReference();
            }

            $wp->share_token = (string) Str::uuid();
            $wp->share_token_expires_at = now()->addDays(1);
        });
    }

    // ==========================================
    // STATIC HELPERS
    // ==========================================

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
                ->firstOrCreate( // FIXED: typo (was FirstOrCreate)
                    ['year' => $year],
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
