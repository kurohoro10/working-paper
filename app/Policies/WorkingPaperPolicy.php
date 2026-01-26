<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkingPaper;

/**
 * Class WorkingPaperPolicy
 *
 * Governs access control for Working Papers.
 */
class WorkingPaperPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if a working paper can be updated.
     */
    public function update(User $user, WorkingPaper $workingPaper): bool
    {
        return $workingPaper->status !== 'finalised';
    }

    /**
     * Determine if a working paper can be finalised.
     */
    public function finalise(User $user, WorkingPaper $workingPaper): bool
    {
        return $workingPaper->status === 'draft';
    }

    /**
     * Determine if PDF download is allowed.
     */
    public function viewPdf(User $user, WorkingPaper $workingPaper): bool
    {
        return true;
    }

    /**
     * Allow delete only for admin users.
     */
    public function delete(User $user, WorkingPaper $workingPaper): bool
    {
        return $user->role === 'admin';
    }
}
