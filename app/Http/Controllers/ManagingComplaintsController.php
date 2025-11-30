<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintCommentRequest;
use App\Http\Requests\ComplaintFileRequest;
use App\Http\Requests\CreateComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Models\Complaint;
use App\Models\Complaint_attachment;
use App\Models\User;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ManagingComplaintsController extends Controller
{
    use AuthorizesRequests;
    private $service;
    public function __construct(ComplaintService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $this->authorize('view', Complaint::class);
        $complaints = $this->service->index();
        return sendResponse($complaints, 200, "Getting Complaints For " . auth('sanctum')->user()->First_name . " successfully", false);
    }
    public function OneComplaint($id)
    {
        $complaint =  Complaint::find($id);
        $this->authorize('viewAny', $complaint);
        $complaintData = $this->service->OneComplaint($complaint);
        return sendResponse($complaintData, 200, "Getting data For Complaint Done ", false);
    }
    public function update(UpdateComplaintRequest $request)
    {
        $complaintData = $request->validated();
        $this->authorize('update', Complaint::find($complaintData['id']));
        $complaint = $this->service->update($complaintData);
        return sendResponse($complaint, 200, "Updating Complaint Done", false);
    }
    public function create(CreateComplaintRequest $request)
    {
        $complaintData = $request->validated();
        $this->authorize('create', Complaint::class);
        $complaint = $this->service->createcomplaint($complaintData);
        return sendResponse($complaint, 200, "Creating Complaint Done", false);
    }
    public function delete(Request $request)
    {
        $request->validate(['id' => 'required|exists:complaints,id']);
        $this->authorize('delete', Complaint::find($request->input('id')));
        $this->service->delete($request->input('id'));
        return sendResponse(null, 200, "Deleting Complaint Done", false);
    }
    public function add_comment_complaint(ComplaintCommentRequest $request)
    {
        $data = $request->validated();
        $this->authorize('add_comment', Complaint::find($data['complaint_id']));
        $comment = $this->service->add_comment($data);
        return sendResponse($comment, 200, "Adding Complaint Comment Done", false);
    }
    public function add_attachment_complaint(ComplaintFileRequest $request)
    {
        $data = $request->validated();
        $complaint = Complaint::findOrFail($data['complaint_id']);
        $this->authorize('add_attachment_complaint', $complaint);
        $attachment = $this->service->add_attachment_complaint($data);
        return sendResponse($attachment, 201, "Attachment added successfully", false);
    }
}
