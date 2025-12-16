<?php

namespace App\Services\complaintservices;

use App\contracts\ComplaintManagmentInterface;
use App\Models\Complaint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SupervisorAdminComplaintManagment implements ComplaintManagmentInterface
{
    // Implementation for supervisor to get all complaints
    public function index()
    {
        // getting all complaints and caching them
        $user = auth('sanctum')->user();
        $complaints = Cache::remember($user->First_name . $user->id . 'complaints', 10, function () use ($user) {
            return Complaint::where('agency_id', $user->agency_id)->get();
        });
        // register logging info
        Log::info('Supervisor ' . auth('sanctum')->user()->First_name . ' fetched all complaints.');
        //retyrning response
        return $complaints;
    }

    // Implementation for supervis  or to update a complaint
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
                Log::info('Supervisor Admin ' . auth('sanctum')->user()->First_name . ' updated complaint with ID: ' . $id);
                // Adding Sending Notificatrion Service calling
            });
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
    // Implementation for supervisor to delete a complaint
    public function delete($id)
    {
        // delete complaint
        Complaint::findOrFail($id)->delete();
        // register logging info
        Log::info('Supervisor ' . auth('sanctum')->user()->First_name . ' deleted complaint with ID: ' . $id);
        // sending response
        return true;
    }
    public function add_comment_complaint($data) {}
    public function add_attachment_complaint($data) {}
    public function create($data) {}
    public function OneComplaint($id) {}
}
