<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingPaper extends Model
{
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function auditLogs()
    {
        return $this->morphMay(AuditLog::class, 'auditable');
    }
}
