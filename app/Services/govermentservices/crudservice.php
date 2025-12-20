<?php

namespace App\Services\govermentservices;

use App\contracts\GovermentAgencyInterface;
use App\Models\GovernmentAgencie;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class crudservice implements GovermentAgencyInterface
{
    public function index()
    {
        // getting all goverment agencies
        $agencies = GovernmentAgencie::all();
        // add logging information
        Log::info("Fetched all government agencies by admin", ['count' => $agencies->count()]);
        // returning response
        return $agencies;
    }
    public function create($data)
    {
        try {
            DB::transaction(function () use ($data) {
                // updating agency
                GovernmentAgencie::create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'address' => $data['address'],
                    'contact_email' => $data['contact_email'],
                    'contact_phone' => $data['contact_phone'],
                ]);
                // logging information
                Log::info("Created new government agency", ['name' => $data['name']]);
            });
        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء إنشاء الجهة الحكومية.',
                'Message' => $e->getMessage(),
                'Line' => $e->getLine()
            ], 500);
        }


        return true;
    }
    public function update($id, $data)
    {
        try {
            DB::transaction(function () use ($id, $data) {
                // updating agency
                $goverment =   GovernmentAgencie::findOrFail($id);
                $goverment->update([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'address' => $data['address'],
                    'contact_email' => $data['contact_email'],
                    'contact_phone' => $data['contact_phone'],
                ]);
                // logging information
                Log::info("Updated government agency", ['name : ' => $goverment->name]);
            });
        } catch (Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء تحديث الجهة الحكومية.',
                'Message' => $e->getMessage(),
                'Line' => $e->getLine()
            ], 500);
        }

        // returning response
        return true;
    }
    public function delete($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // deleting agency
                GovernmentAgencie::find($id)->delete();
                // logging information
                Log::info("Deleted government agency", ['id' => $id]);
            });
            // returning response
            return true;
        } catch (Exception $e) {
            return response()->json([
                'Error' => 'حدث خطأ أثناء حذف الجهة الحكومية.',
                'Error Message' => $e->getMessage(),
                'Error Line' => $e->getLine()
            ], 500);
        }
    }
}
