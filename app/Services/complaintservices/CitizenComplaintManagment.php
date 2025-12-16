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

class CitizenComplaintManagment implements ComplaintManagmentInterface
{
    // Implementation for employee to get all complaints
    public function index()
    {
        // getting all complaints and caching them
        $user = auth('sanctum')->user();
        $complaints = Cache::remember($user->First_name . $user->id . 'complaints', 10, function () use ($user) {
            return Complaint::where([
                'citizen_id' => $user->id
            ])->get([
                'id',
                'title',
                'description',
                'type'
            ]);
        });
        // register logging info
        Log::info('Citizin ' . auth('sanctum')->user()->First_name . ' fetched all complaints.');
        //retyrning response
        return $complaints;
    }
    // implementation creation of Complaint by client
    public function create($data)
    {
        // create new complaint 
        DB::transaction(function () use ($data) {
            Complaint::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'type' => $data['type'],
                'priority' => $data['priority'],
                'status' => 'new',
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude']  ?? null,
                'address_text' => $data['address_text'] ?? null,
                'is_locked' => false,
                'citizen_id' => auth('sanctum')->user()->id,
                'agency_id' => $data['agency_id'],
                'employee_id' => null,
                'created_at' => now()
            ]);
            // register log info 
            Log::info("create new Complaint by client " . auth('sanctum')->user()->First_name);
        });
        // returning response 
        return true;
    }
    // implementation of getting one complaint 
    public function OneComplaint($id)
    {
        $complaint = Complaint::find($id);
        // loading data for one complaint 
        $complaint->load([
            'attachments',
            'comments.user',
            'logs.user',
            'employee',
            'client'
        ]);
        //handle date in array 
        $data = collect([
            'complaint' => [
                'id'          => $complaint->id ?? null,
                'title'       => $complaint->title,
                'description' => $complaint->description,
                'type'        => $complaint->type,
                'priority'    => $complaint->priority,
                'status'      => $complaint->status,
                'location'    => [
                    'latitude'  => $complaint->latitude,
                    'longitude' => $complaint->longitude,
                    'address'   => $complaint->text_address,
                ],
                'employee' => $complaint->employee,
                'client'   => $complaint->client,
                'created_at' => $complaint->created_at,
            ],
            'files' => $complaint->attachments->map(function ($file) {
                return [
                    'id'        => $file->id ?? null,
                    'type'      => $file->file_type,
                    'path'      => asset('storage/' . $file->file_path),
                    'desc'      => $file->description,
                ];
            }),
            'comments' => $complaint->comments->map(function ($comment) {
                return [
                    'id'        => $comment->id ?? null,
                    'message'   => $comment->message,
                    'internal'  => $comment->is_internal,
                    'user'      => [
                        'id'   => $comment->user->id ?? null,
                        'name' => $comment->user->name,
                        'role' => $comment->user->role,
                    ],
                    'created_at' => $comment->created_at,
                ];
            }),

            'events' => $complaint->logs->map(function ($log) {
                return [
                    'id'       => $log->id ?? null,
                    'note'     => $log->nots,
                    'user'     => [
                        'id'   => $log->user->id ?? null,
                        'name' => $log->user->name,
                        'role' => $log->user->role,
                    ],
                    'created_at' => $log->created_at,
                ];
            }),
        ]);
        // register log info 
        Log::info("getting details of complaint " . $complaint->title . " by citizen" . auth('sanctum')->user()->First_name);
        // returning response
        return $data;
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
                Log::info('citizen ' . auth('sanctum')->user()->First_name . ' updated complaint with ID: ' . $id);
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
            Log::info('citizen ' . auth('sanctum')->user()->First_name . ' added comment to complaint with ID: ' . $data['complaint_id']);
            // Adding Sending Notification Service
            /**
             * 
             */
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
        // storing file path in database
        DB::transaction(function () use ($data, $file_path, $extension) {
            Complaint_attachment::create([
                'file_path'    => $file_path,
                'file_type'    => $extension,
                'complaint_id' => $data['complaint_id'],
                'description'  => $data['description'] ?? null,
            ]);
            // register logging info
            Log::info('citizen ' . auth('sanctum')->user()->First_name . ' added attachment to complaint with ID: ' . $data['complaint_id']);
                 // Adding Sending Notification Service
            /**
             * 
             */
        });
        // returning response
        return true;
    }
    public function delete($id) {}
}
