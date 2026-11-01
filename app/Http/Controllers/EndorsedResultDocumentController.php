<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\EndorsedResultDocument;
use App\Models\Pngo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class EndorsedResultDocumentController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderIndex($request);
    }

    public function store(Request $request)
    {
        $validated = $this->validateDocument($request, true);
        $month = Carbon::createFromFormat('Y-m', $validated['reporting_month'])->startOfMonth();

        $this->ensureUniqueDistrictMonth((int) $validated['district_id'], $month);

        $district = District::findOrFail($validated['district_id']);
        $pngo = Pngo::findOrFail($validated['pngo_id']);
        $stored = $this->storeFile($request->file('file'), $district, $pngo, $month);

        try {
            DB::transaction(function () use ($district, $pngo, $month, $stored) {
                EndorsedResultDocument::create([
                    'district_id' => $district->id,
                    'pngo_id' => $pngo->id,
                    'reporting_month' => $month->toDateString(),
                    'document_title' => $this->documentTitle($district, $pngo, $month),
                    'original_file_name' => $stored['original_file_name'],
                    'stored_file_name' => $stored['stored_file_name'],
                    'file_path' => $stored['file_path'],
                    'mime_type' => $stored['mime_type'],
                    'file_size' => $stored['file_size'],
                    'uploaded_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($stored['file_path']);
            throw $exception;
        }

        return redirect()
            ->route('endorsed-documents.index', [
                'district_id' => $district->id,
                'reporting_month' => $month->format('Y-m'),
            ])
            ->with('success', 'Signed endorsed report uploaded successfully.');
    }

    public function edit(Request $request, EndorsedResultDocument $endorsedDocument)
    {
        $this->authorizeScope($endorsedDocument);

        return $this->renderIndex($request, $endorsedDocument);
    }

    public function update(Request $request, EndorsedResultDocument $endorsedDocument)
    {
        $this->authorizeScope($endorsedDocument);

        $validated = $this->validateDocument($request, false);
        $month = Carbon::createFromFormat('Y-m', $validated['reporting_month'])->startOfMonth();

        $this->ensureUniqueDistrictMonth(
            (int) $validated['district_id'],
            $month,
            $endorsedDocument->id
        );

        $district = District::findOrFail($validated['district_id']);
        $pngo = Pngo::findOrFail($validated['pngo_id']);
        $oldPath = $endorsedDocument->file_path;
        $stored = $request->hasFile('file')
            ? $this->storeFile($request->file('file'), $district, $pngo, $month)
            : null;

        try {
            DB::transaction(function () use ($endorsedDocument, $district, $pngo, $month, $stored) {
                $data = [
                    'district_id' => $district->id,
                    'pngo_id' => $pngo->id,
                    'reporting_month' => $month->toDateString(),
                    'document_title' => $this->documentTitle($district, $pngo, $month),
                    'updated_by' => Auth::id(),
                ];

                if ($stored) {
                    $data = array_merge($data, $stored);
                }

                $endorsedDocument->update($data);
            });
        } catch (Throwable $exception) {
            if ($stored) {
                Storage::disk('local')->delete($stored['file_path']);
            }

            throw $exception;
        }

        if ($stored && $oldPath !== $stored['file_path']) {
            Storage::disk('local')->delete($oldPath);
        }

        return redirect()
            ->route('endorsed-documents.index', [
                'district_id' => $district->id,
                'reporting_month' => $month->format('Y-m'),
            ])
            ->with('success', 'Signed endorsed report updated successfully.');
    }

    public function view(EndorsedResultDocument $endorsedDocument)
    {
        $this->authorizeScope($endorsedDocument);
        $this->ensureFileExists($endorsedDocument);

        return response()->file(
            Storage::disk('local')->path($endorsedDocument->file_path),
            [
                'Content-Type' => $endorsedDocument->mime_type,
                'Content-Disposition' => 'inline; filename="'.$endorsedDocument->stored_file_name.'"',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

    public function download(EndorsedResultDocument $endorsedDocument)
    {
        $this->authorizeScope($endorsedDocument);
        $this->ensureFileExists($endorsedDocument);

        return Storage::disk('local')->download(
            $endorsedDocument->file_path,
            $endorsedDocument->stored_file_name,
            ['Content-Type' => $endorsedDocument->mime_type]
        );
    }

    public function destroy(EndorsedResultDocument $endorsedDocument)
    {
        $this->authorizeScope($endorsedDocument);
        $filePath = $endorsedDocument->file_path;

        DB::transaction(fn () => $endorsedDocument->delete());
        Storage::disk('local')->delete($filePath);

        return redirect()
            ->route('endorsed-documents.index')
            ->with('success', 'Signed endorsed report deleted successfully.');
    }

    private function renderIndex(Request $request, ?EndorsedResultDocument $editDocument = null)
    {
        $user = Auth::user();
        $query = $user->applyDistrictPngoScope(
            EndorsedResultDocument::query(),
            'district_id',
            'pngo_id'
        )->with(['district:id,name', 'pngo:id,name', 'uploader:id,name,full_name', 'updater:id,name,full_name']);

        $storedYears = (clone $query)
            ->selectRaw('YEAR(reporting_month) as report_year')
            ->distinct()
            ->orderByDesc('report_year')
            ->pluck('report_year')
            ->map(fn ($year) => (int) $year)
            ->all();

        $defaultYears = range((int) now()->year, 2023);
        $years = collect(array_merge($storedYears, $defaultYears))
            ->unique()
            ->sortDesc()
            ->values();

        $query
            ->when($request->filled('district_id'), fn ($query) => $query->where('district_id', $request->district_id))
            ->when($request->filled('pngo_id'), fn ($query) => $query->where('pngo_id', $request->pngo_id));

        if ($request->filled('reporting_month') && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $request->reporting_month)) {
            $month = Carbon::createFromFormat('Y-m', $request->reporting_month)->startOfMonth();
            $query->whereDate('reporting_month', $month->toDateString());
        } elseif ($request->filled('filter_year') && preg_match('/^\d{4}$/', $request->filter_year)) {
            $query->whereYear('reporting_month', (int) $request->filter_year);
        }

        [$districts, $pngos] = $this->allowedDistrictsAndPngos();

        return view('dashboard.admin.endorsed-result-documents', [
            'documents' => $query->latest('reporting_month')->latest('updated_at')->paginate(25)->withQueryString(),
            'districts' => $districts,
            'pngos' => $pngos,
            'years' => $years,
            'filters' => $request->only(['district_id', 'pngo_id', 'filter_year', 'reporting_month']),
            'editDocument' => $editDocument,
        ]);
    }

    private function validateDocument(Request $request, bool $fileRequired): array
    {
        $validated = $request->validate([
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'pngo_id' => ['required', 'integer', 'exists:pngos,id'],
            'reporting_month' => ['required', 'date_format:Y-m'],
            'file' => [$fileRequired ? 'required' : 'nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'mimetypes:application/pdf,image/png,image/jpeg', 'max:10240'],
        ], [
            'file.required' => 'Please select the signed PDF or image to upload.',
            'file.mimes' => 'The signed document must be a PDF, PNG, JPG, or JPEG file.',
            'file.mimetypes' => 'The selected file content is not a valid PDF or supported image.',
            'file.max' => 'The signed document may not be larger than 10 MB.',
        ]);

        $pngoBelongsToDistrict = Pngo::whereKey($validated['pngo_id'])
            ->where('district_id', $validated['district_id'])
            ->exists();

        if (! $pngoBelongsToDistrict) {
            throw ValidationException::withMessages([
                'pngo_id' => 'The selected PNGO does not belong to the selected district.',
            ]);
        }

        if (! Auth::user()->canAccessDistrictPngo($validated['district_id'], $validated['pngo_id'])) {
            abort(403, 'You do not have access to the selected district and PNGO.');
        }

        return $validated;
    }

    private function ensureUniqueDistrictMonth(int $districtId, Carbon $month, ?int $exceptId = null): void
    {
        $exists = EndorsedResultDocument::query()
            ->where('district_id', $districtId)
            ->whereDate('reporting_month', $month->toDateString())
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'reporting_month' => 'A signed endorsed report already exists for this district and month. Use Edit/Replace on the existing report.',
            ]);
        }
    }

    private function storeFile(UploadedFile $file, District $district, Pngo $pngo, Carbon $month): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $baseName = implode('_', [
            'project_achievement_endorsed_report',
            Str::slug($district->name, '_'),
            Str::slug($pngo->name, '_'),
            $month->format('Y_m'),
            now()->format('YmdHis'),
            Str::lower(Str::random(6)),
        ]);
        $storedFileName = $baseName.'.'.$extension;
        $directory = 'endorsed-results/'.$month->format('Y/m').'/district-'.$district->id;
        $filePath = Storage::disk('local')->putFileAs($directory, $file, $storedFileName);

        if (! $filePath) {
            throw ValidationException::withMessages([
                'file' => 'The signed document could not be stored. Please try again.',
            ]);
        }

        return [
            'original_file_name' => $file->getClientOriginalName(),
            'stored_file_name' => $storedFileName,
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType() ?: $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ];
    }

    private function documentTitle(District $district, Pngo $pngo, Carbon $month): string
    {
        return 'Project Achievement Endorsed Report - '.$district->name.' - '.$pngo->name.' - '.$month->format('F Y');
    }

    private function authorizeScope(EndorsedResultDocument $document): void
    {
        abort_unless(
            Auth::user()->canAccessDistrictPngo($document->district_id, $document->pngo_id),
            403,
            'You do not have access to this signed endorsed report.'
        );
    }

    private function ensureFileExists(EndorsedResultDocument $document): void
    {
        abort_unless(Storage::disk('local')->exists($document->file_path), 404, 'The stored document file was not found.');
    }

    private function allowedDistrictsAndPngos(): array
    {
        $user = Auth::user();
        $districtIds = $user->accessibleDistrictIds();
        $pngoIds = $user->accessiblePngoIds();

        $districts = District::query()
            ->when(is_array($districtIds), fn ($query) => $query->whereIn('id', $districtIds))
            ->orderBy('name')
            ->get(['id', 'name']);

        $pngos = Pngo::query()
            ->when(is_array($pngoIds), fn ($query) => $query->whereIn('id', $pngoIds))
            ->orderBy('name')
            ->get(['id', 'district_id', 'name']);

        return [$districts, $pngos];
    }
}
