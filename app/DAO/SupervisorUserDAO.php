<?php

namespace App\DAO;

use App\Models\User;
use App\DAO\Interfaces\UserDAOInterface;
use Illuminate\Support\Facades\Cache;

// use Symfony\Component\HttpKernel\Attribute\Cache;

class SupervisorUserDAO implements UserDAOInterface
{
    public function getUsers()
    {

        $users = Cache::remember(auth('sanctum')->user()->name . 'supervisor_users', 10, function () {
            return User::where([
                'role' => 'employee',
                'agency_id' => auth('sanctum')->user()->agency_id
            ])->get();
        });
        return $users;
    }
    public function createUser(array $data)
    {
        // $data['role'] = 'employee';
        // $data['agency_id'] = auth('sanctum')->user()->agency_id;
        // return User::create($data);
        return User::create([
            'First_name' => $data['First_name'],  // استخدم lowercase
            'Last_name' => $data['Last_name'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => 'employee',
            'agency_id' => auth('sanctum')->user()->agency_id,
        ]);
    }
    public function updateUser(array $data)
    {
        $user = User::find($data['id']);
        $user->update($data);
        return $user;
    }
    public function deleteUser($id)
    {
        return User::where('agency_id', auth('sanctum')->user()->agency_id)
            ->where('role', 'employee')
            ->findOrFail($id)
            ->delete();
    }
}
