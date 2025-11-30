<?php

namespace App\Http\Controllers;

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
    private $service;
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $this->authorize('view', auth('sanctum')->user());
        $users =  $this->service->index();
        return  response()->json([
            'status' => 'succesfully',
            'users' => $users
        ]);
    }
    public function create(CreateUserRequest $request)
    {
        $data =  $request->validated();
        $this->authorize('create', User::class);
        $user = $this->service->create($data);
        return  sendResponse($user, 201, "Creating " . $request->input('role') . " Done", false);
    }
    public function update(UpdateUserRequest $request)
    {
        $data =  $request->validated();
        $user = User::findOrFail($data['id']);
        $this->authorize('update', $user);
        $user = $this->service->update($data);
        return  sendResponse($user, 200, "Updating " . $request->input('role') . " Done", false);
    }
    public function delete(Request $request)
    {
        $request->validate(['id' => 'required | exists:users,id']);
        $user = User::find($request->input('id'));
        $this->authorize('delete', $user);
        $user->tokens()->delete();
        $user->delete();
        return sendResponse(null, 200, "Deleting User Done", false);
    }
}
