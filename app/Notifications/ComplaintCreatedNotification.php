<?php
namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ComplaintCreatedNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public Complaint $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'complaint.created',
            'complaint_id' => $this->complaint->id,
            'title' => 'شكوى جديدة',
            'message' => 'تم تقديم شكوى جديدة بعنوان: ' . $this->complaint->title,
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'complaint_id' => $this->complaint->id,
            'title' => $this->complaint->title,
            'status' => 'new'
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('agencies.' . $this->complaint->agency_id);
    }
}