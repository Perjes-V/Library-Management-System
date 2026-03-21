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

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
$(document).ready(function(){

    // ================= CSRF SETUP =================
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ================= AJAX SUBMIT =================
    $('#addCategoryForm').on('submit', function(e){
        e.preventDefault(); // prevent normal form submit

        let formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response){
                // Show success alert
                $('#ajax-messages').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${response.message || 'Category added successfully!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);

                // Optional: clear input
                $('#addCategoryForm')[0].reset();

                // Redirect to masterlist after 1.5 seconds
                setTimeout(() => {
                    window.location.href = "{{ route('categories.index') }}";
                }, 1500);
            },
            error: function(xhr){
                let errors = xhr.responseJSON?.errors;
                let errorHtml = '';

                if(errors){
                    $.each(errors, function(key, value){
                        errorHtml += `<li>${value[0]}</li>`;
                    });
                    errorHtml = `<ul>${errorHtml}</ul>`;
                } else {
                    errorHtml = `<p>${xhr.responseJSON?.message || 'Failed to add category.'}</p>`;
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