<?php

// DashboardController.php

namespace App\Http\Controllers;
use App\Models\District;
use App\Models\Pngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CommonService;
use App\Services\LogService;


class DashboardController extends Controller
{
    public function index()
    {
        $commonService = new CommonService();
        // $data = $commonService->showCaseAssistanceData(Null,Null,Null);
        $districtWise = $commonService->showCaseAssistanceDistrictWise();
        $pngoWise = $commonService->showCaseAssistancePngoWise();
        // dd($pngoWise);

        return view('dashboard.admin.dashboard', compact('districtWise', 'pngoWise'));
    }

    public function districts()
    {
        $districts = District::all();
        return view('dashboard.admin.district', compact('districts'));
    }
    // Function to Add a New District
    public function districtAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:districts,name',
        ]);

        $district = new District();
        $district->name = $request->name;
        $district->save();

        // Log the creation
        LogService::logAction('District Created', [
            'district_id' => $district->id,
            'district_name' => $district->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'District added successfully!',
            'district' => $district,
        ]);
    }


    // Function to Update an Existing District
    public function districtUpdate(Request $request, $districtId)
    {
        $request->validate([
            'name' => 'required|unique:districts,name,' . $districtId,
        ]);

        $district = District::findOrFail($districtId);

        $changes = [];

        if ($district->name !== $request->name) {
            $changes['name'] = [
                'from' => $district->name,
                'to' => $request->name,
            ];
        }

        $district->name = $request->name;
        $district->save();

        if (!empty($changes)) {
            LogService::logAction('District Updated', [
                'district_id' => $district->id,
                'changed_fields' => $changes,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'District updated successfully!',
            'district' => $district,
        ]);
    }


    // Function to Delete a District
    public function districtDelete($districtId)
    {
        $district = District::findOrFail($districtId);
        $deletedName = $district->name;

        $district->delete();

        // Log the deletion
        LogService::logAction('District Deleted', [
            'district_id' => $districtId,
            'district_name' => $deletedName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'District deleted successfully!',
        ]);
    }



    public function pngos()
    {
        $pngos = Pngo::all();
        return view('dashboard.admin.pngo', compact('pngos'));
    }
    // Function to Add a New Pngo
    public function pngoAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pngos,name',
        ]);

        // Create a new PNGO
        $pngo = new Pngo();
        $pngo->name = $request->name;
        $pngo->save();

        // Log the creation
        LogService::logAction('PNGO Added', [
            'pngo_id' => $pngo->id,
            'name' => $pngo->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PNGO added successfully!',
            'pngo' => $pngo,
        ]);
    }


    // Function to Update an Existing Pngo
    public function pngoUpdate(Request $request, $pngoId)
    {
        $request->validate([
            'name' => 'required|unique:pngos,name,' . $pngoId,
        ]);

        $pngo = Pngo::findOrFail($pngoId);
        $oldName = $pngo->name;
        $pngo->name = $request->name;
        $pngo->save();

        // Log the update
        LogService::logAction('PNGO Update', [
            'pngo_id' => $pngo->id,
            'changed_fields' => "Name changed from '{$oldName}' to '{$pngo->name}'",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PNGO updated successfully!',
            'pngo' => $pngo,
        ]);
    }


    // Function to Delete a Pngo
    public function pngoDelete($pngoId)
    {
        $pngo = Pngo::findOrFail($pngoId);
        $pngoName = $pngo->name;
        $pngo->delete();

        // Log the delete action
        LogService::logAction('PNGO Delete', [
            'pngo_id' => $pngoId,
            'deleted_name' => $pngoName,
            'message' => "PNGO '{$pngoName}' (ID: {$pngoId}) was deleted.",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PNGO deleted successfully!',
        ]);
    }


    

    

}
