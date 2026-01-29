<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Allows viewing the list of clients
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Allows viewing a specific client
     *
     * @param \App\Models\User $user
     * @param \App\Models\Client $client
     * @return boolean
     */
    public function view(User $user, Client $client): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Allows creation of client
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Allows updating of client
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function update(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Allows deleting a client
     *
     * @param \App\Models\User $user
     * @return boolean
     */
    public function delete(User $user): bool
    {
        return $user->role === 'admin';
    }
}
