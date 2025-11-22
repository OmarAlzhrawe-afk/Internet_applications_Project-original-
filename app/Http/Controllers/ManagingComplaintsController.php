<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagingComplaintsController extends Controller
{
    public function index()
    {
        $Complaints = Complaint::all();
        return  response()->json([
            'status' => 'succesfully',
            'Complaints' => $Complaints
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'nullable',
            'description' => 'nullable',
            'type' => 'nullable|in:type1,type2,type3', // [ "خدمة",'سلوك' , "بنية تحتية"]
            'priority' => 'nullable|in:high,low,medium', //['high', 'low', 'medium']
            'status' => 'nullable', // ['new', 'in_review', 'in_progress', 'awaiting_info', 'resolved', 'rejected', 'closed']
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'text_address' => 'nullable',
        ]);
        $complaint = Complaint::findOrFail($request->input('id'));
        if ($request->filled('title')) {
            $complaint->title = $request->input('title');
        }

        if ($request->filled('description')) {
            $complaint->description = $request->input('description');
        }

        if ($request->filled('type')) {
            $complaint->type = $request->input('type');
        }

        if ($request->filled('priority')) {
            $complaint->priority = $request->input('priority');
        }

        if ($request->filled('status')) {
            $complaint->status = $request->input('status');
        }

        if ($request->filled('latitude')) {
            $complaint->latitude = $request->input('latitude');
        }
        if ($request->filled('longitude')) {
            $complaint->longitude = $request->input('longitude');
        }
        if ($request->filled('text_address')) {
            $complaint->text_address = $request->input('text_address');
        }
        $complaint->save();
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "Updating " . $request->input('role') . " Done"
        ]);
    }
    public function delete(Request $request)
    {
        Complaint::find($request->input('id'))->delete();
        return  response()->json([
            'status' => 'succesfully',
            'Message' => "Deleting Complaint Done"
        ]);
    }
}
