<?php

namespace App\Http\Controllers\V1;

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
use App\Services\NotificationService;

class ManagingComplaintsController extends Controller
{
    use AuthorizesRequests;
    private $complaintservice;
    private $notificationservice;
    public function __construct(ComplaintManagmentInterface $complaintservice, NotificationService
    $notificationservice)
    {
        $this->complaintservice = $complaintservice;
        $this->notificationservice = new NotificationService();
    }
    public function index()
    {
        // check permission
        $this->authorize('view', Complaint::class);
        // get complaints
        $complaints = $this->complaintservice->index();
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('getting all complaints')
            ->log(" User " . auth('sanctum')->user()->First_name . " getting all complaints ");
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
        // sending notifications for users related to this complaint
        $this->notificationservice->complaintupdated(Complaint::find($data['id']), auth('sanctum')->user());
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('update complaint')
            ->log(" User " . auth('sanctum')->user()->First_name . " update Complaint Id : " . $data['id']);

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
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('delete complaint')
            ->log(" User " . auth('sanctum')->user()->First_name . " delete Complaint Id : " . $request->input('id'));

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
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('create complaint')
            ->log(" User " . auth('sanctum')->user()->First_name . " create new Complaint  ");
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
        // sending notification 
        $this->notificationservice->addcomment(Complaint::find($data['complaint_id']), auth('sanctum')->user());
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('add_comment complaint')
            ->log(" User " . auth('sanctum')->user()->First_name . " add_comment For Complaint  ");
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
        // sending notification 
        $this->notificationservice->addattachment(Complaint::find($data['complaint_id']), auth('sanctum')->user());
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('add_attachment complaint')
            ->log(" User " . auth('sanctum')->user()->First_name . " add_attachment For Complaint  ");

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
    public function accept_complaint(Request $request)
    {
        // validate request
        $data = $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'employee_id' => 'required|exists:users,id',
        ]);
        // check permission
        $complaint = Complaint::find($data['complaint_id']);
        $this->authorize('accept_complaint', $complaint);
        // accept complaint
        $response = $this->complaintservice->accept_complaint($data);
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('Accept complaint')
            ->log(" User " . auth('sanctum')->user()->First_name . " Accept Complaint with Id :  " . $request->input('complaint_id'));
        // sending response
        if ($response) {
            return sendResponse(null, 200, "Accepting Complaint Done", false);
        } else {
            return sendResponse(null, 423, $response['message'], true);
        }
    }
}
