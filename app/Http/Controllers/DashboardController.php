<?php

// DashboardController.php

namespace App\Http\Controllers;
use App\Models\District;
use App\Models\Pngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CommonService;

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

        // Create a new district
        $district = new District();
        $district->name = $request->name;
        $district->save();

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
        $district->name = $request->name;
        $district->save();

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
        $district->delete();

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

        // Create a new pngo
        $pngo = new Pngo();
        $pngo->name = $request->name;
        $pngo->save();

        return response()->json([
            'success' => true,
            'message' => 'PNGO added successfully!',
            'pngo' => $pngo,
        ]);
    }

    // Function to Update an Existing Pngo
    public function pngoUpdate(Request $request, $districtId)
    {
        $request->validate([
            'name' => 'required|unique:pngos,name,' . $districtId,
        ]);

        $pngo = Pngo::findOrFail($districtId);
        $pngo->name = $request->name;
        $pngo->save();

        return response()->json([
            'success' => true,
            'message' => 'Pngo updated successfully!',
            'pngo' => $pngo,
        ]);
    }

    // Function to Delete a Pngo
    public function pngoDelete($districtId)
    {
        $pngo = Pngo::findOrFail($districtId);
        $pngo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pngo deleted successfully!',
        ]);
    }

    

}
