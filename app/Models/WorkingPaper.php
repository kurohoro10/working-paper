<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingPaper extends Model
{
    protected $fillable = [
        'client_name',
        'service',
        'job_reference',
        'period',
        'status',
        'finalised_at',
        'snapshot_pdf_path',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
