<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComplaintPolicy
{
    public function create(User $authUser)
    {
        return $authUser->role === 'client';
    }
    public function view(User $authUser)
    {
        return in_array($authUser->role, ['super_admin', 'supervisor', 'employee', 'client']);
        // return true;
    }
    public function accept_complaint(User $user, Complaint $complaint)
    {
        return in_array($user->role, ['super_admin', 'supervisor']) && $complaint->agency_id === $user->agency_id && $complaint->status === 'new';
    }
    public function viewAny(User $user, Complaint $complaint)
    {
        switch ($user->role) {
            case 'super_admin':
                return true;
            case 'supervisor':
                return $complaint->agency_id === $user->agency_id;

            case 'employee':
                return $complaint->employee_id === $user->id;

            case 'client':
                return $complaint->citizen_id === $user->id;

            default:
                return false;
        }
    }

    public function update(User $user, Complaint $complaint)
    {
        switch ($user->role) {
            case 'super_admin':
                return true;

            case 'supervisor':
                return $complaint->agency_id === $user->agency_id;
                // return true;
            case 'employee':
                return $complaint->employee_id === $user->id;

            case 'client':
                return $complaint->citizen_id === $user->id;
            default:
                return false;
        }
    }
    public function delete(User $user, Complaint $complaint)
    {
        switch ($user->role) {
            case 'super_admin':
                return true;

            case 'supervisor':
                return $complaint->agency_id === $user->agency_id;

            case 'employee':
                return $complaint->employee_id === $user->id;

            case 'citizen':
                return $complaint->user_id === $user->id;

            default:
                return false;
        }
    }
    public function add_comment(User $authUser, Complaint $complaint)
    {
        if ($authUser->role == 'employee') {
            return $complaint->employee_id === $authUser->id;
        } elseif ($authUser->role == 'client') {
            return $complaint->citizen_id === $authUser->id;
        } else {
            return false;
        }
    }
    public function add_attachment_complaint(User $authUser, Complaint $complaint)
    {
        if ($authUser->role == 'employee') {
            return $complaint->employee_id === $authUser->id;
        } elseif ($authUser->role == 'client') {
            return $complaint->citizen_id === $authUser->id;
        } else {
            return false;
        }
    }
}
