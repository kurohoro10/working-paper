<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Only admins can create users.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'endurego_internal']);
    }
}
