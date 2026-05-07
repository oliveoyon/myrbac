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

                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>


        </div>


    </section>
@endsection
