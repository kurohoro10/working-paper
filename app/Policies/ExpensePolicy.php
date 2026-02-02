<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Expense $expense): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal', 'client']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    /**
     * User can update an expense if:
     * - they own the working paper
     * - the working paper is NOT finalised
     */
    public function update(User $user, Expense $expense): bool
    {
        return
            in_array($user->role, ['admin', 'endurego_internal']) &&
            $expense->workingPaper->status !== 'finalised';
    }

    /**
     * User can delete an expense if:
     * - they own the working paper
     * - the working paper is NOT finalised
     */
    public function delete(User $user, Expense $expense): bool
    {
        return
            in_array($user->role, ['admin', 'endurego_internal']) &&
            $expense->workingPaper->status !== 'finalised';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Expense $expense): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Expense $expense): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    /**
     * Determin if the user can add internal comments.
     */
    public function addInternalComment(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }
}
