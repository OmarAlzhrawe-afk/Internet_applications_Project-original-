<?php

namespace App\Services;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Support\Collection;
use App\Notifications\Complaint\ComplaintAcceptedNotification;
use App\Notifications\Complaint\ComplaintLockedNotification;
use App\Notifications\Complaint\ComplaintCreatedNotification;
use App\Notifications\ComplaintAcceptedNotification as NotificationsComplaintAcceptedNotification;

class NotificationService
{
    public function notifyUser(User $user, $notification): void
    {
        $user->notify($notification);
    }
    public function notifyUsers(Collection $users, $notification): void
    {
        foreach ($users as $user) {
            $user->notify($notification);
        }
    }
    // public function complaintCreated(Complaint $complaint): void
    // {
    //     $supervisors = User::where('role', 'supervisor')
    //         ->where('agency_id', $complaint->agency_id)
    //         ->get();

    //     $this->notifyUsers(
    //         $supervisors,
    //         new ComplaintCreatedNotification($complaint)
    //     );
    // }

    public function complaintAccepted(Complaint $complaint, User $byUser): void
    {
        // المواطن
        $this->notifyUser(
            $complaint->client,
            new NotificationsComplaintAcceptedNotification($complaint, $byUser)
        );

        // // الموظف المعين
        // if ($complaint->employee) {
        //     $this->notifyUser(
        //         $complaint->employee,
        //         new NotificationsComplaintAcceptedNotification($complaint, $byUser)
        //     );
        // }
    }

    // public function complaintLocked(Complaint $complaint, User $lockedBy, User $failedUser): void
    // {
    //     $this->notifyUser(
    //         $failedUser,
    //         new ComplaintLockedNotification($complaint, $lockedBy)
    //     );
    // }
}
