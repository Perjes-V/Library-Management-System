@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-tags me-2"></i> Categories</h3>
                    <a href="{{ route('categories.create') }}" class="btn btn-light btn-sm fw-bold">
                        <i class="bi bi-plus-circle-fill me-1"></i> Add Category
                    </a>
                </div>

                <div class="card-body p-4">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Category ID</th>
                                        <th>Category Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr id="row_{{ $category->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <!-- Edit Button -->
                                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                                </a>

                                                <!-- AJAX Delete Button -->
                                                <button class="btn btn-sm btn-danger deleteCategoryBtn" data-id="{{ $category->id }}">
                                                    <i class="bi bi-trash me-1"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted mb-0">No categories found.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
