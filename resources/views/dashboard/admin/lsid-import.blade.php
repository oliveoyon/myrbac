@extends('dashboard.layouts.admin-layout')

@section('title', 'Bulk LSID Register Import')

@push('styles')
<style>
    .lsid-import-page {
        display: grid;
        gap: 14px;
        color: #17202a;
    }

    .lsid-import-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .lsid-import-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .lsid-import-header h1 {
        margin: 0;
        color: #1f2937;
        font-size: 17px;
        font-weight: 800;
    }

    .lsid-import-header small {
        color: #64748b;
    }

    .lsid-import-body {
        padding: 16px;
    }

    .lsid-import-notes {
        display: grid;
        gap: 8px;
        margin: 0 0 14px;
        padding: 12px 14px;
        border: 1px solid #d7dee3;
        border-radius: 8px;
        background: #fbfcfd;
        color: #475569;
        font-size: 13px;
    }

    .lsid-import-notes strong {
        color: #1f2937;
    }

    .lsid-import-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    @media (max-width: 576px) {
        .lsid-import-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .lsid-import-actions,
        .lsid-import-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<section class="lsid-import-page">
    <div class="lsid-import-panel">
        <div class="lsid-import-header">
            <div>
                <h1>Bulk LSID Register Import</h1>
                <small>Imports old LSID Excel data into the current LSID register structure.</small>
            </div>
            <a href="{{ route('lsid-register.manage') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-table-list"></i> Management
            </a>
        </div>
        <div class="lsid-import-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session('import_errors'))
                <div class="alert alert-danger">
                    <strong>Import stopped. Please fix these row errors and upload again.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach (session('import_errors') as $importError)
                            <li>{{ $importError }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="lsid-import-notes">
                <div><strong>Expected sheet:</strong> Data_Sheet. If missing, the first active sheet will be used.</div>
                <div><strong>Expected data columns:</strong> columns A to L from your old LSID workbook. Full files may start at row 6; split files without headings may start at row 1.</div>
                <div><strong>Generated ID:</strong> Excel old ID is ignored. The system creates district-wise IDs like BAR-LSID-1.</div>
                <div><strong>Safety:</strong> the full file is validated first. If any row has an error, no LSID rows will be imported.</div>
            </div>

            <form action="{{ route('lsid-register.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Excel / CSV File</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                </div>
                <div class="lsid-import-actions">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-import"></i> Import LSID Registers
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
