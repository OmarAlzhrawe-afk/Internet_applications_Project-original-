<?php

namespace App\DAO;

use App\Models\User;
use App\DAO\Interfaces\UserDAOInterface;
use Illuminate\Support\Facades\Cache;

class SuperAdminUserDAO implements UserDAOInterface
{
    public function getUsers()
    {
        $users = Cache::remember(auth('sanctum')->user()->name . auth('sanctum')->user()->id . 'users', 10, function () {
            return   User::with('agency:name')->get([
                'First_name',
                'Last_name',
                'email',
                'phone_number',
                // 'agency',
                'role',
            ]);
        });
        return $users;
    }

    public function createUser(array $data): User
    {
        // return User::create($data);
        // dd($data);
        return User::create([
            'First_name' => $data['First_name'],  // استخدم lowercase
            'Last_name' => $data['Last_name'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'agency_id' => $data['agency_id'] ?? null,
        ]);
    }

    public function updateUser(array $data): User
    {
        $user = User::find($data['id']);
        $user->update($data);
        return $user;
    }

    public function deleteUser($id): User
    {
        return User::findOrFail($id)->delete();
    }
}
