<?php

namespace App\Services\userservices;

use App\contracts\UserManagingInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuperAdminUserManagmentService implements UserManagingInterface
{
    public function index()
    {
        // getting users with caching
        $users = Cache::remember(auth('sanctum')->user()->name . auth('sanctum')->user()->id . 'users', 10, function () {
            return   User::with('agency:name')->get([
                'id',
                'First_name',
                'Last_name',
                'email',
                'phone_number',
                // 'agency',
                'role',
            ]);
        });
        // register logging data 
        Log::info('User ' . auth('sanctum')->user()->name . ' with id ' . auth('sanctum')->user()->id . ' fetched all users at ' . now());
        // returning results 
        return $users;
    }
    public function create($data)
    {
        try {
            DB::transaction(function () use ($data) {
                // creation User
                return User::create([
                    'First_name' => $data['First_name'],  // استخدم lowercase
                    'Last_name' => $data['Last_name'],
                    'phone_number' => $data['phone_number'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'role' => $data['role'],
                    'agency_id' => $data['agency_id'] ?? null,
                ]);
                // register logging data
                Log::info('User ' . auth('sanctum')->user()->name . ' with id ' . auth('sanctum')->user()->id . ' created a new user with email ' . $data['email'] . ' at ' . now());
            });
        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء إنشاء المستخدم.',
                'details' => $e->getMessage(),
                'details' => $e->getLine()
            ], 500);
        }

        // returning results
        return true;
    }
    public function update($id, $data)
    {
        try {
            // getting user
            $user = User::find($id);
            DB::transaction(function () use ($user, $data, $id) {
                // updating user
                $user->update($data);
                // register logging data
                Log::info('User ' . auth('sanctum')->user()->name . ' with id ' . auth('sanctum')->user()->id . ' updated user with id ' . $id . ' at ' . now());
            });
            // returning results    
            return $user;
        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء تحديث المستخدم.',
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
                // deleting user
                $user->tokens()->delete();
                $user->delete();
                // register logging data
                Log::info('User ' . auth('sanctum')->user()->name . ' with id ' . auth('sanctum')->user()->id . ' deleted user with id ' . $id . ' at ' . now());
            });
            // returning results    
            return true;
        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء حذف المستخدم.',
                'details' => $e->getMessage(),
                'details' => $e->getLine()
            ], 500);
        }
    }
}
