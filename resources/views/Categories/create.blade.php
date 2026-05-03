@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add Category</h5>
        </div>

        <div class="card-body">
            {{-- AJAX Messages --}}
            <div id="ajax-messages"></div>

            <form id="addCategoryForm" action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           placeholder="Enter category name"
                           required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('categories.index') }}" 
                       class="btn btn-secondary">
                        Back
                    </a>

                    <button type="submit" 
                            class="btn btn-success">
                        Save Category
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
