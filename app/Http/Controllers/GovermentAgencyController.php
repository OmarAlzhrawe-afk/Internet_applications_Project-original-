<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GovernmentAgencie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GovermentAgencyController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('view', GovernmentAgencie::class);

        $agencies = GovernmentAgencie::all();
        return  response()->json([
            'status' => 'succesfully',
            'agencies' => $agencies
        ]);
    }
    public function create(Request $request)
    {
        $this->authorize('create', GovernmentAgencie::class);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'address' => 'required',
            'contact_email' => 'required',
            'contact_phone' => 'required',
        ]);
        GovernmentAgencie::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'address' => $request->input('address'),
            'contact_email' => $request->input('contact_email'),
            'contact_phone' => $request->input('contact_phone'),

        ]);
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "Creating Agency Done"
        ]);
    }
    public function update(Request $request)
    {
        $this->authorize('update', GovernmentAgencie::class);

        $request->validate([
            'id' => 'required',
            'name' => 'nullable',
            'description' => 'nullable',
            'address' => 'nullable',
            'contact_email' => 'nullable',
            'contact_phone' => 'nullable',
        ]);

        GovernmentAgencie::where('id', $request->input('id'))->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'address' => $request->input('address'),
            'contact_email' => $request->input('contact_email'),
            'contact_phone' => $request->input('contact_phone'),

        ]);
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "updating Agency Done"
        ]);
    }
    public function delete(Request $request)
    {
        $this->authorize('delete', GovernmentAgencie::class);

        GovernmentAgencie::find($request->input('id'))->delete();
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "Deleting Agency Done"
        ]);
    }
}
