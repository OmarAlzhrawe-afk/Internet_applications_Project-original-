<?php

namespace App\Http\Controllers;

use app\contracts\GovermentAgencyInterface;
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
        // returning response
        return sendResponse(null, 200, "Updating Agency Done", false);
    }
    public function delete(Request $request)
    {
        // checking permissions
        $this->authorize('delete', GovernmentAgencie::class);
        // deleting agency
        $this->govermentAgencyInterface->delete($request->input('id'));
        // returning response
        return sendResponse(null, 200, "Deleting Agency Done", false);
    }
}
