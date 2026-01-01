<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BackupFinishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        // نرسل الإشعار عبر قاعدة البيانات (للحفظ) والـ Broadcast (لـ Reverb)
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'النسخ الاحتياطي',
            'message' => 'تم الانتهاء من عملية النسخ الاحتياطي للنظام بنجاح.',
            'time' => now()->toDateTimeString(),
        ];
    }

    // هذا الجزء هو المسؤول عن Reverb
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'النسخ الاحتياطي',
            'message' => 'تم الانتهاء من عملية النسخ الاحتياطي للنظام بنجاح.',
        ]);
    }
}
