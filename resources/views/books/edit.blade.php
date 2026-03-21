@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Edit Book</h3>
                </div>

                <div class="card-body p-4">

                    {{-- AJAX Messages --}}
                    <div id="ajax-messages"></div>

                    <form id="editBookForm" action="{{ route('books.update', $book->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" id="title" class="form-control rounded-3"
                                   placeholder="Enter Book Title" value="{{ old('title', $book->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label fw-semibold">Author</label>
                            <input type="text" name="author" id="author" class="form-control rounded-3"
                                   placeholder="Enter Author Name" value="{{ old('author', $book->author) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">Category</label>
                            <select name="category_id" id="category_id" class="form-select rounded-3" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                                <i class="bi bi-save me-2"></i> Update Book
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ================= AJAX SUBMIT =================
    $('#editBookForm').on('submit', function(e){
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST', // Laravel expects POST + PUT via _method
            data: formData,
            success: function(response){
                // Success alert
                $('#ajax-messages').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${response.message || 'Book updated successfully!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);

                // Redirect to masterlist after short delay
                setTimeout(() => {
                    window.location.href = "{{ route('books.index') }}";
                }, 1200);
            },
            error: function(xhr){
                let errors = xhr.responseJSON?.errors;
                let errorHtml = '';

                if(errors){
                    $.each(errors, function(key, value){
                        if(Array.isArray(value)){
                            errorHtml += `<li>${value[0]}</li>`;
                        } else {
                            errorHtml += `<li>${value}</li>`;
                        }
                    });
                    errorHtml = `<ul>${errorHtml}</ul>`;
                } else {
                    errorHtml = `<p>${xhr.responseJSON?.message || 'Failed to update book.'}</p>`;
                }

                $('#ajax-messages').html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${errorHtml}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);
            }
        });
    });

});
</script>
@endsection