<?php

namespace App\Http\Controllers;

use App\contracts\UserManagingInterface;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ManagingUsersController extends Controller
{
    use AuthorizesRequests;
    private $userservice;
    public function __construct(UserManagingInterface $userservice)
    {
        $this->userservice = $userservice;
    }
    public function index()
    {
        // check permession
        $this->authorize('view', auth('sanctum')->user());
        // get users
        $users =  $this->userservice->index();
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('Getting Users')
            ->log(" User " . auth('sanctum')->user()->First_name . " Getting Users Agency");
        // returning response
        return sendResponse($users, 200, "Getting Users Done", false);
    }
    public function create(CreateUserRequest $request)
    {
        // validating data
        $data =  $request->validated();
        // check permession
        $this->authorize('create', User::class);
        // create user
        $user = $this->userservice->create($data);
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('Create User')
            ->log(" User " . auth('sanctum')->user()->First_name . " creating User");
        // sending response
        return  sendResponse(null, 201, "Creating " . $request->input('role') . " Done", false);
    }
    public function update(UpdateUserRequest $request)
    {
        // validating data
        $data =  $request->validated();
        // check permession
        $user = User::findOrFail($data['id']);
        $this->authorize('update', $user);
        // updating user
        $user = $this->userservice->update($data['id'], $data);
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('update User')
            ->log(" User " . auth('sanctum')->user()->First_name . " update User Id : " . $data['id']);

        // sending response
        return  sendResponse(null, 200, " Updating " . $user->role . "  " . $user->First_name . " Done", false);
    }
    public function delete(Request $request)
    {
        // validating data
        $request->validate(['id' => 'required | exists:users,id']);
        // check permession
        $user = User::find($request->input('id'));
        $this->authorize('delete', $user);
        // deleting user
        $result = $this->userservice->delete($request->input('id'));
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('Delete User')
            ->log(" User " . auth('sanctum')->user()->First_name . " Delete User  : " . $request->input('id'));
        // sending response
        if ($result) {
            return sendResponse(null, 200, "Deleting User Done", false);
        }
    }
}
