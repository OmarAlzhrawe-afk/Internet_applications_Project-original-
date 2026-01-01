<?php

namespace App\Services;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Support\Collection;
use App\Notifications\ComplaintCreatedNotification;
use App\Notifications\ComplaintAcceptedNotification as NotificationsComplaintAcceptedNotification;
use App\Notifications\AcceptComplaintNotificationForEmployee;
use App\Notifications\AddComplaintAttachmentNotification;
use App\Notifications\AddComplaintCommentNotification;
use App\Notifications\UpdateComplaintNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function notifyUser(User $user, $notification): void
    {
        $user->notify($notification);
    }
    public function notifyUsers(Collection $users, $notification): void
    {
        Notification::send($users, $notification);
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
    public function complaintCreated(Complaint $complaint): void
    {
        $supervisors = User::where('agency_id', $complaint->agency_id)
            ->where('role', 'supervisor')
            ->get();

        Notification::send($supervisors, new ComplaintCreatedNotification($complaint));
    }
    public function complaintAccepted(Complaint $complaint, User $byUser): void
    {
        if ($complaint->client) {
            $this->notifyUser($complaint->client, new NotificationsComplaintAcceptedNotification($complaint, $byUser));
        }
        if ($complaint->employee) {
            $this->notifyUser($complaint->employee, new AcceptComplaintNotificationForEmployee($complaint, $byUser));
        }
    }
    public function complaintupdated(Complaint $complaint, User $byUser)
    {
        if ($byUser->role == 'super_admin') {
            if ($complaint->client) {
                $this->notifyUser($complaint->client, new UpdateComplaintNotification($complaint, $byUser));
            }
            if ($complaint->client) {
                $this->notifyUser($complaint->employee, new UpdateComplaintNotification($complaint, $byUser));
            }
            if ($complaint->client) {
                $this->notifyUser(User::find($complaint->agency_id), new UpdateComplaintNotification($complaint, $byUser));
            }
        } else if ($byUser->role == 'supervisor') {
            if ($complaint->client) {
                $this->notifyUser($complaint->client, new UpdateComplaintNotification($complaint, $byUser));
            }
            if ($complaint->client) {
                $this->notifyUser($complaint->employee, new UpdateComplaintNotification($complaint, $byUser));
            }
        } else if ($byUser->role == 'employee') {
            if ($complaint->client) {
                $this->notifyUser($complaint->client, new UpdateComplaintNotification($complaint, $byUser));
            }
        }
    }
    public function addcomment(Complaint $complaint, User $byUser)
    {
        if ($byUser->role == 'employee') {
            if ($complaint->client) {
                $this->notifyUser($complaint->client, new AddComplaintCommentNotification($complaint, $byUser));
            }
        } else if ($byUser->role == 'client') {
            if ($complaint->employee) {
                $this->notifyUser($complaint->employee, new AddComplaintCommentNotification($complaint, $byUser));
            }
        }
    }
    public function addattachment(Complaint $complaint, User $byUser)
    {
        if ($byUser->role == 'employee') {
            if ($complaint->client) {
                $this->notifyUser($complaint->client, new AddComplaintAttachmentNotification($complaint, $byUser));
            }
        } else if ($byUser->role == 'client') {
            if ($complaint->employee) {
                $this->notifyUser($complaint->employee, new AddComplaintAttachmentNotification($complaint, $byUser));
            }
        }
    }

    // public function complaintLocked(Complaint $complaint, User $lockedBy, User $failedUser): void
    // {
    //     $this->notifyUser(
    //         $failedUser,
    //         new ComplaintLockedNotification($complaint, $lockedBy)
    //     );
    // }
}
