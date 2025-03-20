@extends('dashboard.layouts.admin-layout')

@section('title', 'Category Management')


@section('content')
    <section>
        <div class="container-fluid">
            <div class="row mb-3">
                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" required>
                    <button type="submit">Import</button>
                </form>
            </div>


        </div>


    </section>
@endsection
