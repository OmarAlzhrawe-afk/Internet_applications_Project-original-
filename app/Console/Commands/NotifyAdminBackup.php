<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\BackupFinishedNotification;
use Illuminate\Console\Command;

class NotifyAdminBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:admin_backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command to send notification for admin when the DB back up or Clean done';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::where('role', 'super_admin')->first();
        if ($admin) {
            $admin->notify(new BackupFinishedNotification());
            $this->info("success Sending Notification Backup database Done For Admin");
        } else {
            $this->error("Admin Not Found");
        }
    }
}
