<?php

namespace App\Notifications;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
// use Laravel\Reverb\Protocols\Pusher\Channels\PrivateCacheChannel;
// use Laravel\Reverb\Protocols\Pusher\Channels\CacheChannel;
// use Laravel\Reverb\Protocols\Pusher\Channels\PrivateChannel;
// ... استيراد الكلاسات القياسية فقط
use Illuminate\Broadcasting\PrivateChannel;

class AcceptComplaintNotificationForEmployee extends Notification implements ShouldQueue,ShouldBroadcast
{
    use Queueable;
    public $notifiable;
    private Complaint $complaint;
    private User $byUser;
    public function __construct(Complaint $complaint, User $byUser)
    {
        $this->complaint = $complaint;
        $this->byUser = $byUser;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return $notifiable->
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => 'complaint.accepted',
            'message' => 'complaint ' . $this->complaint->title . ' are Accepted',
            'complaint_id' => $this->complaint->id,
        ];
    }
    public function toBroadcast($notifiable) : BroadcastMessage
    {
        // return new BroadcastMessage($this->toArray());
        return new BroadcastMessage([
            'message' =>"new Complaint Assign For you By Supervisor :  " . $this->byUser->name ,
             'type' => 'complaint.Assigned',
            'title' => 'new Complaint',
        ]) ;
    }
    public function broadcastOn()
    {
        return new PrivateChannel('users.' . $this->complaint->employee->id);
    }
}
