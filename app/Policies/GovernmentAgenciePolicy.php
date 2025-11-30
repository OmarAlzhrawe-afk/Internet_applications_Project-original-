<?php

namespace App\Policies;

use App\Models\GovernmentAgencie;
use App\Models\User;

class GovernmentAgenciePolicy
{
    // /**
    //  * Create a new policy instance.
    //  */
    // public function __construct() {

    // }
    public function view(User $user)
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'supervisor') {
            return false;
        }

        if ($user->role === 'employee') {
            return false;
        }

        if ($user->role === 'citizen') {
            return false;
        }
        return false;
    }
    public function create(User $user)
    {
        if ($user->role === 'super_admin') {
            return true;
        } else {
            return false;
        }
    }
    public function update(User $user)
    {
        if ($user->role === 'super_admin') {
            return true;
        } else {
            return false;
        }
    }
    public function delete(User $user)
    {
        if ($user->role === 'super_admin') {
            return true;
        } else {
            return false;
        }
    }
}
