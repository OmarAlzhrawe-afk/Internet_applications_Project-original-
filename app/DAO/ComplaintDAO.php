<?php

namespace App\DAO;

use App\DAO\interfaces\ComplaintDAOInterface;
use App\Models\Complaint;
use Illuminate\Support\Facades\Cache;

class ComplaintDAO implements ComplaintDAOInterface
{
    public function find($id)
    {
        return Complaint::findOrFail($id);
    }

    public function getAll()
    {
        // 'super_admin', 'supervisor', 'employee', 'client'
        $complaints = Cache::remember(auth('sanctum')->user()->name . auth('sanctum')->user()->id . 'complaints', 10, function () {
            $user = auth('sanctum')->user();
            switch ($user->role) {
                case 'super_admin':
                    return Complaint::all();
                    break;
                case 'supervisor':
                    return Complaint::where('agency_id', $user->agency_id)->get();
                    break;
                case 'employee':
                    return Complaint::where([
                        'agency_id' => $user->agency_id,
                        'employee_id' => $user->id
                    ])->get();
                    break;
                case 'client':
                    return Complaint::where([
                        'citizen_id' => $user->id
                    ])->get();
                    break;
            }
        });
        return $complaints;
    }

    public function update($id, array $data)
    {
        $c = Complaint::findOrFail($id);
        $c->update($data);
        return $c;
    }

    public function delete($id): void
    {
        Complaint::findOrFail($id)->delete();
    }
}
