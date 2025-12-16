<?php

namespace App\Services\userservices;

use App\contracts\UserManagingInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupervisorAdminUserManagmentService implements UserManagingInterface
{
    public function index()
    {
        // getting users with caching
        $users = Cache::remember(auth('sanctum')->user()->name . auth('sanctum')->user()->id . 'users', 10, function () {
            //with('agency:name')->
            return   User::where([
                'role' => 'employee',
                'agency_id' => auth('sanctum')->user()->agency_id
            ])->get([
                'id',
                'First_name',
                'Last_name',
                'email',
                'phone_number',
                'role',
            ]);
        });
        // register logging data 
        Log::info('Supervisor ' . auth('sanctum')->user()->name . ' with id ' . auth('sanctum')->user()->id . ' fetched all users at ' . now());
        // returning results 
        return $users;
    }
    public function create($data)
    {
        try {
            DB::transaction(function ($data) {
                // creation User
                return User::create([
                    'First_name' => $data['First_name'],  // استخدم lowercase
                    'Last_name' => $data['Last_name'],
                    'phone_number' => $data['phone_number'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'role' => 'employee',
                    'agency_id' => auth('sanctum')->user()->agency_id,
                ]);
                // register logging data
                Log::info('Supervisor ' . auth('sanctum')->user()->name . ' with id ' . auth('sanctum')->user()->id . ' created a new user with email ' . $data['email'] . ' at ' . now());
            });
            // returning results
            return true;
        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء إنشاء المستخدم.',
                'details' => $e->getMessage(),
                'details' => $e->getLine()
            ], 500);
        }
    }
    public function update($id, $data)
    {
        try {
            // getting user
            $user = User::find($id);
            DB::transaction(function () use ($user, $id, $data) {
                // updating user
                $user->update($data);
                // register logging data
                Log::info('Supervisor ' . auth('sanctum')->user()->name . ' with id ' . auth('sanctum')->user()->id . ' updated user with id ' . $id . ' at ' . now());
            });
        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء إنشاء المستخدم.',
                'details' => $e->getMessage(),
                'details' => $e->getLine()
            ], 500);
        }
    }
    public function delete($id)
    {
        try {
            $user = User::find($id);
            DB::transaction(function () use ($user, $id) {
                $user->tokens()->delete();
                $user->delete();
                // register logging data
                Log::info('Supervisor ' . auth('sanctum')->user()->name . ' with id ' . auth('sanctum')->user()->id . ' deleted user with id ' . $id . ' at ' . now());
            });
            return true;
        } catch (Exception $e) {
            return response()->json([
                'Error' => 'حدث خطأ أثناء حذف المستخدم.',
                'Error Message' => $e->getMessage(),
                'Error Line' => $e->getLine()
            ], 500);
        }
    }
}
