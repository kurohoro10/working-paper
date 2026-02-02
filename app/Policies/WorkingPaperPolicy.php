<?php
/**
 * WorkingPaperPolicy
 *
 * Controls authorization for working paper actions.
 */
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
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    public function view(): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    public function store(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    /**
     * Determine if a working paper can be updated.
     */
    public function update(User $user, WorkingPaper $workingPaper): bool
    {
        return ($user->id === $workingPaper->user_id || $user->is_admin) && $workingPaper->status !== 'finalised';
    }

    /**
     * Determine if a working paper can be finalised.
     */
    public function finalise(User $user, WorkingPaper $workingPaper): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']) && $workingPaper->status === 'draft';
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
        return in_array($user->role, ['admin', 'endurego_internal']) && $workingPaper->status !== 'finalised';
    }
}
