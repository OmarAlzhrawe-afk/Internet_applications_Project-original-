<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Complaint_attachment;
use App\Models\Complaint_comment;
use App\Repositories\ComplaintRepositry;
use App\Repositories\UserRepository;

use function Symfony\Component\Clock\now;

class ComplaintService
{
    protected $repo;

    public function __construct(ComplaintRepositry $repo)
    {
        $this->repo = $repo->dao();
    }

    public function index()
    {

        return $this->repo->getAll();
    }

    // public function create(array $data)
    // {
    //     return $this->repo->create($data);
    // }

    public function update(array $data)
    {
        return $this->repo->update($data['id'], $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
    public function add_comment($data)
    {
        return Complaint_comment::create([
            'message' => $data['message'],
            'is_internal' => $data['is_internal'],
            'complaint_id' => $data['complaint_id'],
            'user_id' => auth('sanctum')->user()->id,
        ]);
    }
    public function add_attachment_complaint($data)
    {
        $file = $data['file'];
        $extension = $file->getClientOriginalExtension();
        $file_name = uniqid() . '.' . $extension;
        $file_path = $file->storeAs(
            'complaints_attachments/' . $data['complaint_id'],
            $file_name,
            'public'
        );
        return  Complaint_attachment::create([
            'file_path'    => $file_path,
            'file_type'    => $extension,
            'complaint_id' => $data['complaint_id'],
            'description'  => $data['description'] ?? null,
        ]);
    }
    public function OneComplaint(Complaint $complaint)
    {
        $complaint->load([
            'attachments',
            'comments.user',
            'logs.user',
            'employee',
            'client'
        ]);
        $data = collect([
            'complaint' => [
                'id'          => $complaint->id,
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
                    'id'        => $file->id,
                    'type'      => $file->file_type,
                    'path'      => asset('storage/' . $file->file_path),
                    'desc'      => $file->description,
                ];
            }),

            'comments' => $complaint->comments->map(function ($comment) {
                return [
                    'id'        => $comment->id,
                    'message'   => $comment->message,
                    'internal'  => $comment->is_internal,
                    'user'      => [
                        'id'   => $comment->user->id,
                        'name' => $comment->user->name,
                        'role' => $comment->user->role,
                    ],
                    'created_at' => $comment->created_at,
                ];
            }),

            'events' => $complaint->logs->map(function ($log) {
                return [
                    'id'       => $log->id,
                    'note'     => $log->nots,
                    'user'     => [
                        'id'   => $log->user->id,
                        'name' => $log->user->name,
                        'role' => $log->user->role,
                    ],
                    'created_at' => $log->created_at,
                ];
            }),
        ]);
        return $data;
    }
    public function createcomplaint(array $complaintData)
    {
        return  Complaint::create([
            'title' => $complaintData['title'],
            'description' => $complaintData['description'],
            'type' => $complaintData['type'],
            'priority' => $complaintData['priority'],
            'status' => 'new',
            'latitude' => $complaintData['latitude'] ?? null,
            'longitude' => $complaintData['longitude']  ?? null,
            'address_text' => $complaintData['address_text'] ?? null,
            'is_locked' => false,
            'citizen_id' => auth('sanctum')->user()->id,
            'agency_id' => $complaintData['agency_id'],
            'employee_id' => null,
            'created_at' => now()
        ]);
    }
}
