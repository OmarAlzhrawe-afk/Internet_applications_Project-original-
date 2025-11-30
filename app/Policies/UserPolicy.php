<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $authUser)
    {
        // super admin → can view all users
        if ($authUser->role === 'super_admin') {
            return true;
        }

        // supervisor → can view only users in his agency
        if ($authUser->role === 'supervisor') {
            return true; // Allow listing, filtering will be in service
        }

        return false;
    }
    public function view(User $authUser, User $targetUser)
    {
        // super_admin can view all users
        if ($authUser->role === 'super_admin') {
            return true;
        }
        // supervisor can only view users inside his agency
        if ($authUser->role === 'supervisor') {
            if ($targetUser && $targetUser->agency_id === $authUser->agency_id) {
                return true;
            }
            return false;
        }

        // employee & citizen → no permission
        return false;
    }
    public function create(User $authUser)
    {
        // Only super_admin and supervisor can create users
        return in_array($authUser->role, ['super_admin', 'supervisor']);
    }
    public function update(User $authUser, User $user)
    {
        if (auth('sanctum')->user()->role === 'supervisor') {
            if (!$user ||  $user->agency_id != auth('sanctum')->user()->agency_id) {
                return false;
            }
        }
        return in_array($authUser->role, ['super_admin', 'supervisor']);
    }
    public function delete(User $authUser, User $user)
    {
        if (auth('sanctum')->user()->role === 'supervisor') {
            if (!$user || $user->role = 'employee' &&  $user->agency_id != auth('sanctum')->user()->agency_id) {
                return false;
            }
        }
        return in_array($authUser->role, ['super_admin', 'supervisor']);
    }
}
