<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Allows viewing the list of clients
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    /**
     * Allows viewing a specific client
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function view(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }

    /**
     * Only admins can create users.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }
}
