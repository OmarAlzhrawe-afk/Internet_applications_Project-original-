<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagingUsersController extends Controller
{
    public function index($role)
    {
        $users = User::where('role', $role)->get();
        return  response()->json([
            'status' => 'succesfully',
            'agencies' => $users
        ]);
    }
    public function create(Request $request)
    {
        $request->validate([
            'First_name' => 'required',
            'Last_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'password' => 'required',
            'agency_id' => 'nullable',
            'role' => 'required',
        ]);
        User::create([
            'First_name' => $request->input('First_name'),
            'Last_name' => $request->input('Last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'password' => $request->input('password'),
            'agency_id' => $request->input('agency_id') ?? null,
            'role' => $request->input('role'),
        ]);
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "Creating " . $request->input('role') . " Done"
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'First_name' => 'nullable',
            'Last_name' => 'nullable',
            'email' => 'nullable',
            'phone_number' => 'nullable',
            'password' => 'nullable',
            'agency_id' => 'nullable',
            'role' => 'nullable',
        ]);
        $user = User::findOrFail($request->input('id'));

        if ($request->filled('First_name')) {
            $user->First_name = $request->input('First_name');
        }

        if ($request->filled('Last_name')) {
            $user->Last_name = $request->input('Last_name');
        }

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        if ($request->filled('phone_number')) {
            $user->phone_number = $request->input('phone_number');
        }

        if ($request->filled('agency_id')) {
            $user->agency_id = $request->input('agency_id');
        }

        if ($request->filled('role')) {
            $user->role = $request->input('role');
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "Updating " . $request->input('role') . " Done"
        ]);
    }
    public function delete(Request $request)
    {
        User::find($request->input('id'))->delete();
        // here we will delete tokens for this user
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "Deleting Agency Done"
        ]);
    }
}
