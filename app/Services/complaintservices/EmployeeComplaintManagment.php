<?php

namespace App\Services\complaintservices;

use App\contracts\ComplaintManagmentInterface;
use App\Models\Complaint;
use App\Models\Complaint_comment;
use App\Models\Complaint_attachment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class EmployeeComplaintManagment implements ComplaintManagmentInterface
{
    // Implementation for employee to get all complaints
    public function index()
    {
        // getting all complaints and caching them
        $user = auth('sanctum')->user();
        $complaints = Cache::remember($user->First_name . $user->id . 'complaints', 10, function () use ($user) {
            return Complaint::where([
                'agency_id' => $user->agency_id,
                'employee_id' => $user->id
            ])->get();
        });
        // register logging info
        Log::info('Employee ' . auth('sanctum')->user()->First_name . ' fetched all complaints.');
        //retyrning response
        return $complaints;
    }

    // Implementation for employee to update a complaint
    public function update($id, $data)
    {
        $lockErrorCodes = [
            '23000',
            '40001',
            '1205',
        ];
        $respoonse = [];
        try {
            DB::transaction(function () use ($id, $data) {
                $c = Complaint::where('id', $id)
                    // implementation concrete access for denied updates from other users
                    ->lockForUpdate()
                    ->first();
                // sleep(10);
                $c->update($data);
                // return $c;
                // register logging info
                Log::info('Employee ' . auth('sanctum')->user()->First_name . ' updated complaint with ID: ' . $id);
                // Adding Sending Notificatrion Service calling
            });
            // register logging info
            // returning response
            $respoonse['status'] = true;
            $respoonse['message'] = 'success';
            return $respoonse;
        } catch (QueryException $e) {
            if (in_array($e->getCode(), $lockErrorCodes) || str_contains($e->getMessage(), 'Deadlock')) {
                $respoonse['status'] = false;
                $respoonse['message'] = 'هذه الشكوى قيد التعديل من قبل مستخدم آخر، يرجى المحاولة لاحقاً.';
                return $respoonse;
            }
        }
    }
    public function add_comment_complaint($data)
    {
        DB::transaction(function () use ($data) {
            // create comment for complaint
            Complaint_comment::create([
                'message' => $data['message'],
                'is_internal' => $data['is_internal'],
                'complaint_id' => $data['complaint_id'],
                'user_id' => auth('sanctum')->user()->id,
            ]);
            // register logging info
            Log::info('Employee ' . auth('sanctum')->user()->First_name . ' added comment to complaint with ID: ' . $data['complaint_id']);
            // Adding Sending Notification Service
        });
        // returning response
        return true;
    }
    public function add_attachment_complaint($data)
    {
        // storing file local
        $file = $data['file'];
        $extension = $file->getClientOriginalExtension();
        $file_name = uniqid() . '.' . $extension;
        $file_path = $file->storeAs(
            'complaints_attachments/' . $data['complaint_id'],
            $file_name,
            'public'
        );
        DB::transaction(function () use ($data, $file_path, $extension) {
            // storing file path in database
            Complaint_attachment::create([
                'file_path'    => $file_path,
                'file_type'    => $extension,
                'complaint_id' => $data['complaint_id'],
                'description'  => $data['description'] ?? null,
            ]);
            // register logging info
            Log::info('Employee ' . auth('sanctum')->user()->First_name . ' added attachment to complaint with ID: ' . $data['complaint_id']);
            // Adding Sending Notification Service
            /**
             * 
             * 
             */
        });
        // returning response
        return true;
    }
    public function delete($id) {}
    public function create($data) {}
    public function OneComplaint($id) {}
}
