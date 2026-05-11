<?php

// DashboardController.php

namespace App\Http\Controllers;
use App\Models\District;
use App\Models\FollowUpIntervention;
use App\Models\Pngo;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\CommonService;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $commonService = new CommonService();
        // $data = $commonService->showCaseAssistanceData(Null,Null,Null);
        $districtWise = $commonService->showCaseAssistanceDistrictWise();
        $pngoWise = $commonService->showCaseAssistancePngoWise();
        $todoSummary = $this->dashboardTodoSummary();
        // dd($pngoWise);

        return view('dashboard.admin.dashboard', compact('districtWise', 'pngoWise', 'todoSummary'));
    }

    private function dashboardTodoSummary(): array
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $personalCount = Todo::where('user_id', $user->id)
            ->whereDate('task_date', $today)
            ->where('status', '!=', Todo::STATUS_DONE)
            ->count();

        $followUpQuery = FollowUpIntervention::query()
            ->leftJoin('formal_cases', function ($join) {
                $join->on('follow_up_interventions.central_id', '=', 'formal_cases.id')
                    ->orOn('follow_up_interventions.central_id', '=', 'formal_cases.central_id');
            })
            ->whereNotNull('follow_up_interventions.to_be_taken_date')
            ->whereNotNull('follow_up_interventions.intervention_to_be_taken')
            ->where('follow_up_interventions.task_status', '!=', Todo::STATUS_DONE);

        if ($user->district_id) {
            $followUpQuery->where('formal_cases.district_id', $user->district_id);
        }

        if ($user->pngo_id) {
            $followUpQuery->where('formal_cases.pngo_id', $user->pngo_id);
        }

        $todayFollowUpCount = (clone $followUpQuery)
            ->whereDate('follow_up_interventions.to_be_taken_date', $today)
            ->count();

        $upcoming = collect(range(0, 6))->map(function ($offset) use ($user, $followUpQuery) {
            $date = now()->startOfDay()->addDays($offset);
            $dateString = $date->toDateString();

            $personal = Todo::where('user_id', $user->id)
                ->whereDate('task_date', $dateString)
                ->where('status', '!=', Todo::STATUS_DONE)
                ->count();

            $followUp = (clone $followUpQuery)
                ->whereDate('follow_up_interventions.to_be_taken_date', $dateString)
                ->count();

            return [
                'date' => $dateString,
                'label' => $date->format('j M'),
                'day' => $date->format('D'),
                'total' => $personal + $followUp,
            ];
        });

        return [
            'today_total' => $personalCount + $todayFollowUpCount,
            'upcoming' => $upcoming,
        ];
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
        $pngos = Pngo::with('district:id,name')
            ->orderBy('district_id')
            ->orderBy('name')
            ->get();
        $districts = District::orderBy('name')->get();
        return view('dashboard.admin.pngo', compact('pngos', 'districts'));
    }
    // Function to Add a New Pngo
    public function pngoAdd(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pngos', 'name')->where(fn ($query) => $query->where('district_id', $request->district_id)),
            ],
        ], [
            'name.unique' => 'This PNGO already exists in the selected district.',
        ]);

        // Create a new PNGO
        $pngo = new Pngo();
        $pngo->name = $request->name;
        $pngo->district_id = $request->district_id;
        $pngo->save();

        // Log the creation
        LogService::logAction('PNGO Added', [
            'pngo_id' => $pngo->id,
            'name' => $pngo->name,
            'district_id' => $pngo->district_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PNGO added successfully!',
            'pngo' => $pngo->load('district:id,name'),
        ]);
    }


    // Function to Update an Existing Pngo
    public function pngoUpdate(Request $request, $pngoId)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pngos', 'name')
                    ->where(fn ($query) => $query->where('district_id', $request->district_id))
                    ->ignore($pngoId),
            ],
        ], [
            'name.unique' => 'This PNGO already exists in the selected district.',
        ]);

        $pngo = Pngo::findOrFail($pngoId);
        $oldName = $pngo->name;
        $oldDistrictId = $pngo->district_id;
        $pngo->name = $request->name;
        $pngo->district_id = $request->district_id;
        $pngo->save();

        // Log the update
        LogService::logAction('PNGO Update', [
            'pngo_id' => $pngo->id,
            'changed_fields' => [
                'name' => ['from' => $oldName, 'to' => $pngo->name],
                'district_id' => ['from' => $oldDistrictId, 'to' => $pngo->district_id],
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PNGO updated successfully!',
            'pngo' => $pngo->load('district:id,name'),
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
