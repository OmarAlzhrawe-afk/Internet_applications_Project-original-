<?php

namespace App\Http\Controllers;

use App\contracts\GovermentAgencyInterface;
use App\Http\Requests\CreateAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use Illuminate\Http\Request;
use App\Models\GovernmentAgencie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GovermentAgencyController extends Controller
{
    use AuthorizesRequests;

    protected $govermentAgencyInterface;
    public function __construct(GovermentAgencyInterface $govermentAgencyInterface)
    {
        $this->govermentAgencyInterface = $govermentAgencyInterface;
    }
    public function index()
    {
        // check permission
        $this->authorize('view', GovernmentAgencie::class);
        // fetching agencies
        $agencies = $this->govermentAgencyInterface->index();
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('getting Goverments Agencies')
            ->log(" User " . auth('sanctum')->user()->First_name . " getting Goverments Agencies");
        // returning response
        return sendResponse($agencies, 200, "Fetching Agencies Done", false);
    }
    public function create(CreateAgencyRequest $request)
    {
        // check permission
        $this->authorize('create', GovernmentAgencie::class);
        // validating request
        $data = $request->validated();
        // creating agency
        $creation = $this->govermentAgencyInterface->create($data);
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('Create Goverments Agency')
            ->log(" User " . auth('sanctum')->user()->First_name . " Create Goverments Agency");
        // returning response
        if ($creation) {
            return sendResponse(null, 201, "Creating Agency Done", false);
        }
    }
    public function update(UpdateAgencyRequest $request)
    {
        // checking permission
        $this->authorize('update', GovernmentAgencie::class);
        // validating request
        $data = $request->validated();
        // updating agency
        $this->govermentAgencyInterface->update($data['id'], $data);
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('Update Goverments Agency')
            ->log(" User " . auth('sanctum')->user()->First_name . " Update Goverments Agency");
        // returning response
        return sendResponse(null, 200, "Updating Agency Done", false);
    }
    public function delete(Request $request)
    {
        // checking permissions
        $this->authorize('delete', GovernmentAgencie::class);
        // deleting agency
        $this->govermentAgencyInterface->delete($request->input('id'));
        // logging data 
        activity()
            ->causedBy(auth('sanctum')->user())
            ->event('Delete Goverments Agency')
            ->log(" User " . auth('sanctum')->user()->First_name . " delete Goverments Agency");
        // returning response
        return sendResponse(null, 200, "Deleting Agency Done", false);
    }
}
