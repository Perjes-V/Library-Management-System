@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3 class="mb-0"><i class="bi bi-book-fill me-2"></i> Add Book</h3>
                </div>

                <div class="card-body p-4">
                    {{-- AJAX Messages --}}
                    <div id="ajax-messages"></div>

                    <form id="addBookForm" action="{{ route('books.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" id="title" class="form-control rounded-3" placeholder="Enter Book Title" required>
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label fw-semibold">Author</label>
                            <input type="text" name="author" id="author" class="form-control rounded-3" placeholder="Enter Author Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">Category</label>
                            <select name="category_id" id="category_id" class="form-select rounded-3" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                                <label for="quantity" class="form-label fw-semibold">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control rounded-3" placeholder="Enter number of copies" min="1" value="1" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                                <i class="bi bi-plus-circle-fill me-2"></i> Add Book
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
