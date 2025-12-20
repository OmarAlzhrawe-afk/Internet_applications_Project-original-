<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Broadcasting\PrivateChannel;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = "users";
    protected $fillable = [
        'id',
        'First_name',
        'Last_name',
        'email',
        'phone_number',
        'password',
        'agency_id',
        'role',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relations
    public function agency()
    {
        return $this->belongsTo(GovernmentAgencie::class); // , "agency_id", "id"
    }
//      public function receivesBroadcastNotificationsOn(): string
//     {
// return [new PrivateChannel('users.' . $this->id)];    }
public function receivesBroadcastNotificationsOn(): array
{
    // لارفيل سيستخدم هذه الدالة تلقائياً لكل مستخدم موجود في قائمة المستلمين
    return [new PrivateChannel('users.' . $this->id)];
}
}
