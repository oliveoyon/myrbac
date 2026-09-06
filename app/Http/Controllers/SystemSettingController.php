<?php

namespace App\Http\Controllers;

use App\Services\AppSettings;
use App\Services\LogService;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index(AppSettings $settings)
    {
        $settings->syncDefinitions();

        return view('dashboard.admin.system-settings', [
            'definitions' => $settings->definitions(),
            'values' => $settings->valuesForForm(),
        ]);
    }

    public function update(Request $request, AppSettings $settings)
    {
        $definitions = $settings->definitions();

        $validated = $request->validate([
            'case_entry_interview_date_cutoff_day' => 'required|integer|min:1|max:28',
            'formal_case_upload_max_files' => 'required|integer|min:1|max:50',
            'formal_case_upload_max_size_mb' => 'required|integer|min:1|max:20',
            'report_header_title' => 'nullable|string|max:255',
            'report_header_subtitle' => 'nullable|string|max:500',
        ], [
            'case_entry_interview_date_cutoff_day.max' => 'Please use 1 to 28. This avoids invalid dates in February.',
            'formal_case_upload_max_files.max' => 'Maximum attachment files cannot be more than 50.',
            'formal_case_upload_max_size_mb.max' => 'Maximum attachment size cannot be more than 20 MB.',
        ]);

        $settings->update($validated, auth()->id());

        LogService::logAction('System Settings Updated', [
            'updated_by' => auth()->user()->name,
            'settings' => collect($validated)
                ->mapWithKeys(fn ($value, $key) => [$definitions[$key]['label'] ?? $key => $value])
                ->all(),
        ]);

        return redirect()->route('system-settings.index')->with('success', 'System settings updated successfully.');
    }
}
