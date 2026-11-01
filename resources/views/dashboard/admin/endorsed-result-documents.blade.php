@extends('dashboard.layouts.admin-layout')

@section('title', 'Endorsed Result Documents')

@push('styles')
<style>
    .endorsed-page {
        display: grid;
        gap: 16px;
        color: #1f2937;
        min-width: 0;
    }

    .endorsed-header,
    .endorsed-panel {
        border: 1px solid #dfe5e2;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 22px rgba(16, 24, 40, .05);
    }

    .endorsed-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 20px;
        border-left: 4px solid #b71918;
    }

    .endorsed-header h1 {
        margin: 0 0 4px;
        font-size: 21px;
        font-weight: 800;
    }

    .endorsed-header p {
        margin: 0;
        color: #667085;
        font-size: 13px;
    }

    .endorsed-header-icon {
        display: grid;
        width: 42px;
        height: 42px;
        place-items: center;
        border-radius: 8px;
        background: #fbeceb;
        color: #a91515;
        font-size: 19px;
        flex: 0 0 auto;
    }

    .endorsed-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 13px 16px;
        border-bottom: 1px solid #e7ece9;
        background: #f8faf9;
    }

    .endorsed-panel-header h2 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
    }

    .endorsed-panel-body {
        padding: 16px;
    }

    .endorsed-workspace {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
        align-items: start;
        gap: 16px;
    }

    .endorsed-workspace.without-upload {
        grid-template-columns: 1fr;
    }

    .endorsed-form-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .endorsed-filter-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .endorsed-panel.upload-panel {
        border-top: 3px solid #2f7d62;
    }

    .endorsed-panel.filter-panel {
        border-top: 3px solid #6b7280;
    }

    .endorsed-panel-purpose {
        display: block;
        margin-top: 3px;
        color: #667085;
        font-size: 12px;
    }

    .endorsed-field label {
        display: block;
        margin-bottom: 5px;
        color: #344054;
        font-size: 13px;
        font-weight: 700;
    }

    .endorsed-field .required {
        color: #b42318;
    }

    .endorsed-file-field {
        grid-column: 1 / -1;
    }

    .endorsed-title-preview {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 9px;
        min-height: 42px;
        padding: 9px 11px;
        border: 1px solid #dbe8e1;
        border-radius: 7px;
        background: #f5faf7;
        color: #315b49;
        font-size: 13px;
        font-weight: 700;
    }

    .endorsed-title-preview i {
        color: #2f7d62;
    }

    .endorsed-help {
        display: block;
        margin-top: 5px;
        color: #667085;
        font-size: 12px;
    }

    .endorsed-actions,
    .endorsed-filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
    }

    .endorsed-filter-actions {
        justify-content: flex-end;
    }

    .endorsed-alert {
        margin: 0;
        border-radius: 7px;
    }

    .endorsed-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .endorsed-table {
        width: 100%;
        margin: 0;
        font-size: 13px;
    }

    .endorsed-table th {
        padding: 10px;
        color: #344054;
        white-space: nowrap;
        background: #f6f8f7;
    }

    .endorsed-table td {
        padding: 10px;
        vertical-align: middle;
        color: #344054;
    }

    .endorsed-document-title {
        display: block;
        min-width: 230px;
        color: #1f2937;
        font-weight: 750;
    }

    .endorsed-document-meta {
        display: block;
        margin-top: 3px;
        color: #667085;
        font-size: 11px;
    }

    .endorsed-month {
        display: inline-flex;
        padding: 4px 8px;
        border-radius: 5px;
        background: #edf6f1;
        color: #286047;
        font-weight: 750;
        white-space: nowrap;
    }

    .endorsed-row-actions {
        display: flex;
        gap: 5px;
        white-space: nowrap;
    }

    .endorsed-row-actions .btn {
        display: inline-grid;
        width: 31px;
        height: 31px;
        padding: 0;
        place-items: center;
        border-radius: 6px;
    }

    .endorsed-empty {
        padding: 28px 16px;
        color: #667085;
        text-align: center;
    }

    .endorsed-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 13px 16px;
        border-top: 1px solid #e7ece9;
    }

    .endorsed-pagination nav {
        width: 100%;
    }

    .endorsed-pagination .pagination {
        justify-content: center;
        margin: 0;
    }

    @media (max-width: 1050px) {
        .endorsed-workspace {
            grid-template-columns: 1fr;
        }

        .endorsed-form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .endorsed-header {
            align-items: flex-start;
            padding: 15px;
        }

        .endorsed-header h1 {
            font-size: 18px;
        }

        .endorsed-panel-body {
            padding: 13px;
        }

        .endorsed-form-grid {
            grid-template-columns: 1fr;
        }

        .endorsed-file-field,
        .endorsed-title-preview {
            grid-column: auto;
        }

        .endorsed-actions,
        .endorsed-filter-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .endorsed-actions .btn,
        .endorsed-filter-actions .btn {
            width: 100%;
        }

        .endorsed-table thead {
            display: none;
        }

        .endorsed-table,
        .endorsed-table tbody,
        .endorsed-table tr,
        .endorsed-table td {
            display: block;
            width: 100%;
        }

        .endorsed-table tr {
            padding: 8px 12px;
            border-bottom: 1px solid #e7ece9;
        }

        .endorsed-table td {
            display: grid;
            grid-template-columns: 106px minmax(0, 1fr);
            gap: 9px;
            padding: 5px 0;
            border: 0;
        }

        .endorsed-table td::before {
            content: attr(data-label);
            color: #667085;
            font-size: 12px;
            font-weight: 700;
        }

        .endorsed-document-title {
            min-width: 0;
        }
    }
</style>
@endpush

@section('content')
<section class="endorsed-page">
    <div class="endorsed-header">
        <div>
            <h1>Endorsed Result Documents</h1>
            <p>Preserve and retrieve physically signed Project Achievement Reports by district and reporting month.</p>
        </div>
        <span class="endorsed-header-icon"><i class="fas fa-file-signature"></i></span>
    </div>

    @if (session('success'))
        <div class="alert alert-success endorsed-alert">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger endorsed-alert">
            <strong>The document could not be saved.</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $showUploadPanel = $editDocument || auth()->user()->can('Upload Endorsed Result Document');
    @endphp
    <div class="endorsed-workspace {{ $showUploadPanel ? '' : 'without-upload' }}">
    @if ($showUploadPanel)
        <div class="endorsed-panel upload-panel" id="documentFormPanel">
            <div class="endorsed-panel-header">
                <div>
                    <h2>{{ $editDocument ? 'Edit or Replace Signed Report' : 'Upload Signed Report' }}</h2>
                    <span class="endorsed-panel-purpose">Save one signed report for a district and reporting month.</span>
                </div>
                @if ($editDocument)
                    <span class="badge text-bg-warning">Editing existing document</span>
                @endif
            </div>
            <div class="endorsed-panel-body">
                <form method="POST" action="{{ $editDocument ? route('endorsed-documents.update', $editDocument) : route('endorsed-documents.store') }}" enctype="multipart/form-data" id="endorsedDocumentForm">
                    @csrf
                    @if ($editDocument)
                        @method('PUT')
                    @endif

                    @php
                        $formDistrict = old('district_id', $editDocument?->district_id ?? ($districts->count() === 1 ? $districts->first()->id : ''));
                        $formPngo = old('pngo_id', $editDocument?->pngo_id ?? '');
                        $formMonth = old('reporting_month', $editDocument?->reporting_month?->format('Y-m') ?? '');
                    @endphp

                    <div class="endorsed-form-grid">
                        <div class="endorsed-field">
                            <label for="documentDistrict">District <span class="required">*</span></label>
                            <select class="form-select @error('district_id') is-invalid @enderror" id="documentDistrict" name="district_id" required>
                                <option value="">Select district</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}" @selected((string) $formDistrict === (string) $district->id)>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="endorsed-field">
                            <label for="documentPngo">PNGO <span class="required">*</span></label>
                            <select class="form-select @error('pngo_id') is-invalid @enderror" id="documentPngo" name="pngo_id" data-selected="{{ $formPngo }}" required>
                                <option value="">Select district first</option>
                                @foreach ($pngos as $pngo)
                                    <option value="{{ $pngo->id }}" data-district="{{ $pngo->district_id }}">{{ $pngo->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="endorsed-field">
                            <label for="documentMonth">Reporting Month <span class="required">*</span></label>
                            <input type="month" class="form-control @error('reporting_month') is-invalid @enderror" id="documentMonth" name="reporting_month" value="{{ $formMonth }}" required>
                        </div>

                        <div class="endorsed-title-preview">
                            <i class="fas fa-file-lines"></i>
                            <span id="documentTitlePreview">Select district, PNGO, and month to generate the document title.</span>
                        </div>

                        <div class="endorsed-field endorsed-file-field">
                            <label for="endorsedFile">Signed Document {{ $editDocument ? '' : '*' }}</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="endorsedFile" name="file" accept=".pdf,.png,.jpg,.jpeg,application/pdf,image/png,image/jpeg" {{ $editDocument ? '' : 'required' }}>
                            <span class="endorsed-help">
                                PDF, PNG, JPG, or JPEG; maximum 10 MB.
                                @if ($editDocument)
                                    Leave empty to retain the current file: <strong>{{ $editDocument->original_file_name }}</strong>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="endorsed-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas {{ $editDocument ? 'fa-rotate' : 'fa-upload' }}"></i>
                            {{ $editDocument ? 'Update Report' : 'Upload Report' }}
                        </button>
                        @if ($editDocument)
                            <a href="{{ route('endorsed-documents.index', $filters) }}" class="btn btn-outline-secondary">Cancel Edit</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="endorsed-panel filter-panel">
        <div class="endorsed-panel-header">
            <div>
                <h2>Find Signed Reports</h2>
                <span class="endorsed-panel-purpose">Filter the archive. Year and month are alternatives.</span>
            </div>
        </div>
        <div class="endorsed-panel-body">
            <form method="GET" action="{{ route('endorsed-documents.index') }}" id="endorsedFilterForm">
                <div class="endorsed-filter-grid">
                    <div class="endorsed-field">
                        <label for="filterDistrict">District</label>
                        <select class="form-select" id="filterDistrict" name="district_id">
                            <option value="">All permitted districts</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" @selected((string) ($filters['district_id'] ?? '') === (string) $district->id)>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="endorsed-field">
                        <label for="filterPngo">PNGO</label>
                        <select class="form-select" id="filterPngo" name="pngo_id" data-selected="{{ $filters['pngo_id'] ?? '' }}">
                            <option value="">All permitted PNGOs</option>
                            @foreach ($pngos as $pngo)
                                <option value="{{ $pngo->id }}" data-district="{{ $pngo->district_id }}">{{ $pngo->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="endorsed-field">
                        <label for="filterYear">Reporting Year</label>
                        <select class="form-select" id="filterYear" name="filter_year">
                            <option value="">All years</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" @selected((string) ($filters['filter_year'] ?? '') === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="endorsed-field">
                        <label for="filterMonth">Reporting Month</label>
                        <input type="month" class="form-control" id="filterMonth" name="reporting_month" value="{{ $filters['reporting_month'] ?? '' }}">
                        <span class="endorsed-help">Selecting a month clears the yearly filter.</span>
                    </div>
                </div>
                <div class="endorsed-filter-actions">
                    <a href="{{ route('endorsed-documents.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate-left"></i> Reset</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-filter"></i> Apply Filter</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <div class="endorsed-panel">
        <div class="endorsed-panel-header">
            <h2>Signed Document Archive</h2>
            <span class="text-muted small">{{ $documents->total() }} record{{ $documents->total() === 1 ? '' : 's' }}</span>
        </div>
        <div class="endorsed-table-wrap">
            @if ($documents->isEmpty())
                <div class="endorsed-empty">
                    <i class="fas fa-folder-open mb-2"></i><br>
                    No signed endorsed reports were found for the selected filters.
                </div>
            @else
                <table class="table table-bordered endorsed-table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Document</th>
                            <th>District</th>
                            <th>PNGO</th>
                            <th>Month</th>
                            <th>Uploaded By</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                            <tr>
                                <td data-label="SL">{{ $documents->firstItem() + $loop->index }}</td>
                                <td data-label="Document">
                                    <span class="endorsed-document-title">{{ $document->document_title }}</span>
                                    <span class="endorsed-document-meta">{{ strtoupper(pathinfo($document->stored_file_name, PATHINFO_EXTENSION)) }} · {{ number_format($document->file_size / 1048576, 2) }} MB</span>
                                </td>
                                <td data-label="District">{{ $document->district?->name ?? 'N/A' }}</td>
                                <td data-label="PNGO">{{ $document->pngo?->name ?? 'N/A' }}</td>
                                <td data-label="Month"><span class="endorsed-month">{{ $document->reporting_month->format('M Y') }}</span></td>
                                <td data-label="Uploaded By">{{ $document->uploader?->full_name ?: ($document->uploader?->name ?? 'N/A') }}</td>
                                <td data-label="Last Updated">{{ $document->updated_at->format('j M, Y') }}</td>
                                <td data-label="Actions">
                                    <div class="endorsed-row-actions">
                                        @can('Download Endorsed Result Document')
                                            <a href="{{ route('endorsed-documents.view', $document) }}" target="_blank" class="btn btn-outline-success btn-sm" title="View"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('endorsed-documents.download', $document) }}" class="btn btn-outline-secondary btn-sm" title="Download"><i class="fas fa-download"></i></a>
                                        @endcan
                                        @can('Replace Endorsed Result Document')
                                            <a href="{{ route('endorsed-documents.edit', array_merge(['endorsedDocument' => $document], $filters)) }}#documentFormPanel" class="btn btn-outline-warning btn-sm" title="Edit or replace"><i class="fas fa-pen"></i></a>
                                        @endcan
                                        @can('Delete Endorsed Result Document')
                                            <form method="POST" action="{{ route('endorsed-documents.destroy', $document) }}" class="endorsed-delete-form" data-document-label="{{ $document->district?->name ?? 'Selected district' }} - {{ $document->reporting_month->format('F Y') }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if ($documents->hasPages())
            <div class="endorsed-pagination">{{ $documents->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function configurePngoSelect(districtSelect, pngoSelect, allLabel) {
        if (!districtSelect || !pngoSelect) return;

        const options = Array.from(pngoSelect.querySelectorAll('option[data-district]')).map(function (option) {
            return {
                value: option.value,
                text: option.textContent,
                district: option.dataset.district
            };
        });
        let selected = pngoSelect.dataset.selected || '';

        function refresh() {
            const districtId = districtSelect.value;
            const currentValue = pngoSelect.value || selected;
            const matches = districtId
                ? options.filter(function (option) { return option.district === districtId; })
                : [];

            pngoSelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = districtId ? allLabel : 'Select district first';
            pngoSelect.appendChild(placeholder);
            pngoSelect.disabled = !districtId;

            matches.forEach(function (item) {
                const option = document.createElement('option');
                option.value = item.value;
                option.textContent = item.text;
                option.dataset.district = item.district;
                option.selected = item.value === currentValue;
                pngoSelect.appendChild(option);
            });

            if (!pngoSelect.value && districtId && matches.length === 1 && pngoSelect.required) {
                pngoSelect.value = matches[0].value;
            }

            selected = pngoSelect.value;
            pngoSelect.dispatchEvent(new Event('selection-refreshed'));
        }

        districtSelect.addEventListener('change', function () {
            selected = '';
            refresh();
        });
        refresh();
    }

    const documentDistrict = document.getElementById('documentDistrict');
    const documentPngo = document.getElementById('documentPngo');
    const documentMonth = document.getElementById('documentMonth');
    const titlePreview = document.getElementById('documentTitlePreview');

    function updateTitlePreview() {
        if (!titlePreview || !documentDistrict || !documentPngo || !documentMonth) return;

        const district = documentDistrict.options[documentDistrict.selectedIndex]?.text || '';
        const pngo = documentPngo.options[documentPngo.selectedIndex]?.text || '';
        let monthLabel = '';

        if (documentMonth.value) {
            const parts = documentMonth.value.split('-');
            monthLabel = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric', timeZone: 'UTC' })
                .format(new Date(Date.UTC(Number(parts[0]), Number(parts[1]) - 1, 1)));
        }

        titlePreview.textContent = documentDistrict.value && documentPngo.value && monthLabel
            ? 'Project Achievement Endorsed Report - ' + district + ' - ' + pngo + ' - ' + monthLabel
            : 'Select district, PNGO, and month to generate the document title.';
    }

    configurePngoSelect(documentDistrict, documentPngo, 'Select PNGO');
    configurePngoSelect(document.getElementById('filterDistrict'), document.getElementById('filterPngo'), 'All permitted PNGOs');

    const filterYear = document.getElementById('filterYear');
    const filterMonth = document.getElementById('filterMonth');

    filterYear?.addEventListener('change', function () {
        if (filterYear.value && filterMonth) {
            filterMonth.value = '';
        }
    });

    filterMonth?.addEventListener('change', function () {
        if (filterMonth.value && filterYear) {
            filterYear.value = '';
        }
    });

    [documentDistrict, documentPngo, documentMonth].forEach(function (field) {
        field?.addEventListener('change', updateTitlePreview);
        field?.addEventListener('selection-refreshed', updateTitlePreview);
    });
    updateTitlePreview();

    document.getElementById('endorsedDocumentForm')?.addEventListener('submit', function (event) {
        const button = event.submitter;
        if (!button || button.disabled) return;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Saving...';
    });

    document.querySelectorAll('.endorsed-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const documentLabel = form.dataset.documentLabel || 'this signed report';

            if (!window.Swal) {
                if (window.confirm('Delete ' + documentLabel + '? This action cannot be undone.')) {
                    form.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Delete signed report?',
                text: documentLabel + ' will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b42318',
                cancelButtonColor: '#667085',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please keep this page open.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: function () {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
