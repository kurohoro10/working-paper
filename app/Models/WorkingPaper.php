<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingPaper extends Model
{
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
