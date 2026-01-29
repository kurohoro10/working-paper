<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'tax_number',
        'address',
        'is_active',
        'notes',
    ];

    /**
     * Ensure is_active is always treated as boolean.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the working papers for the client.
     *
     * @return HasMany
     */
    public function workingPapers(): HasMany
    {
        return $this->hasMany(WorkingPaper::class);
    }
}
