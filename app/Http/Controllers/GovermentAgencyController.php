<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GovernmentAgencie;

class GovermentAgencyController extends Controller
{
    public function index()
    {
        $agencies = GovernmentAgencie::all();
        return  response()->json([
            'status' => 'succesfully',
            'agencies' => $agencies
        ]);
    }
    public function create(Request $request)
    {
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
    public function delete($id)
    {
        GovernmentAgencie::find($id)->delete();
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "Deleting Agency Done"
        ]);
    }
}
