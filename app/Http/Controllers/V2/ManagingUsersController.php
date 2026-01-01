<?php

namespace App\Http\Controllers\V2;

use App\contracts\UserManagingInterface;
use App\Http\Requests\V2\CreateUserRequest;
use App\Http\Requests\V2\UpdateUserRequest;
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
        // sending response
        if ($result) {
            return sendResponse(null, 200, "Deleting User Done", false);
        }
    }
}
