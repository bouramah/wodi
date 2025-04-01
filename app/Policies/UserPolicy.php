<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    public function view(User $user, User $targetUser)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('agent')) {
            return $targetUser->hasRole('client');
        }

        return false;
    }

    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    public function update(User $user, User $targetUser)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('agent')) {
            return $targetUser->hasRole('client');
        }

        return false;
    }

    public function delete(User $user, User $targetUser)
    {
        return $user->hasRole('admin');
    }
}
