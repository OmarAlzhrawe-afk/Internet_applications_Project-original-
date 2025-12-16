<?php

namespace App\Http\Controllers;

use App\contracts\ComplaintManagmentInterface;
use App\contracts\ComplaintManagmentInterfaceForEmployee;
use App\Http\Requests\ComplaintCommentRequest;
use App\Http\Requests\ComplaintFileRequest;
use App\Http\Requests\CreateComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\interfaces\ComplaintServiceInterface;

class ManagingComplaintsController extends Controller
{
    use AuthorizesRequests;
    private $complaintservice;
    public function __construct(ComplaintManagmentInterface $complaintservice)
    {
        $this->complaintservice = $complaintservice;
    }
    public function index()
    {
        // check permission
        $this->authorize('view', Complaint::class);
        // get complaints
        $complaints = $this->complaintservice->index();
        //sending response
        return sendResponse($complaints, 200, "Getting Complaints For " . auth('sanctum')->user()->First_name . " successfully", false);
    }
    public function update(UpdateComplaintRequest $request)
    {
        // validate request
        $data = $request->validated();
        // check permission
        $this->authorize('update', Complaint::find($data['id']));
        // update complaint
        $response = $this->complaintservice->update($data['id'], $data);
        // sending response
        if ($response['status']) {
            return sendResponse(null, 200, "Updating Complaint Done", false);
        } else {
            return sendResponse(null, 423, $response['message'], true);
        }
    }
    public function delete(Request $request)
    {
        // validate request
        $request->validate(['id' => 'required|exists:complaints,id']);
        // check permission
        $this->authorize('delete', Complaint::find($request->input('id')));
        // delete complaint
        $status = $this->complaintservice->delete($request->input('id'));
        // sending response
        if ($status) {
            return sendResponse(null, 200, "Deleting Complaint Done", false);
        }
    }
    public function create(CreateComplaintRequest $request)
    {
        // validate request
        $data = $request->validated();
        // check permission
        $this->authorize('create', Complaint::class);
        // create complaint
        $status =  $this->complaintservice->create($data);
        // sending response
        if ($status) {
            return sendResponse(null, 200, "Creating Complaint Done", false);
        }
    }
    public function add_comment_complaint(ComplaintCommentRequest $request)
    {
        // validate request
        $data = $request->validated();
        // check permission
        $this->authorize('add_comment', Complaint::find($data['complaint_id']));
        // add comment to complaint
        $comment = $this->complaintservice->add_comment_complaint($data);
        // sending response
        return sendResponse($comment, 200, "Adding Complaint Comment Done", false);
    }
    public function add_attachment_complaint(ComplaintFileRequest $request)
    {
        // validate request
        $data = $request->validated();
        // check permission
        $complaint = Complaint::findOrFail($data['complaint_id']);
        // check permission
        $this->authorize('add_attachment_complaint', $complaint);
        // add attachment to complaint
        $attachment = $this->complaintservice->add_attachment_complaint($data);
        // sending response
        return sendResponse($attachment, 201, "Attachment added successfully", false);
    }
    public function OneComplaint($id)
    {
        $complaint =  Complaint::find($id);
        $this->authorize('viewAny', $complaint);
        $complaintData = $this->complaintservice->OneComplaint($id);
        return sendResponse($complaintData, 200, "Getting data For Complaint Done ", false);
    }
}
