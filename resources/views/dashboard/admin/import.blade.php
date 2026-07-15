@extends('dashboard.layouts.admin-layout')

@section('title', 'Bulk Formal Case Import')


@section('content')
    <section>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 mb-3">
                    <a href="{{ route('import.template') }}" class="btn btn-success">
                        Download Excel Template
                    </a>
                </div>

                @if (session('success'))
                    <div class="col-12">
                        <div class="alert alert-success">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="col-12">
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    </div>
                @endif

                @if (session('import_errors'))
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <strong>Import stopped. Please fix these row errors and upload again.</strong>
                            <ul class="mb-0 mt-2">
                                @foreach (session('import_errors') as $importError)
                                    <li>{{ $importError }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">The full file is validated first. If any row has an error, no cases will be imported.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>


        </div>


    </section>
@endsection
