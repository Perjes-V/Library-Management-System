@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3 class="mb-0">Add Student</h3>
                </div>

                <div class="card-body p-4">

                    {{-- AJAX Messages --}}
                    <div id="ajax-messages"></div>

                    <form id="addStudentForm" action="{{ route('students.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="student_id" class="form-label fw-semibold">Student ID</label>
                            <input type="text" name="student_id" id="student_id" class="form-control rounded-3" placeholder="Enter Student ID" required>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control rounded-3" placeholder="Enter Full Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="course" class="form-label fw-semibold">Course</label>
                            <input type="text" name="course" id="course" class="form-control rounded-3" placeholder="Enter Course" required>
                        </div>

                        <div class="mb-3">
                            <label for="year_level" class="form-label fw-semibold">Year Level</label>
                            <input type="text" name="year_level" id="year_level" class="form-control rounded-3" placeholder="Enter Year Level" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                                <i class="bi bi-person-plus-fill me-2"></i> Add Student
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

<script>
$(document).ready(function(){

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ================= AJAX SUBMIT =================
    $('#addStudentForm').on('submit', function(e){
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response){
                // Show success message
                $('#ajax-messages').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${response.message || 'Student added successfully!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);

                // Optional: Clear form fields
                $('#addStudentForm')[0].reset();

                // Redirect to masterlist after short delay
                setTimeout(() => {
                    window.location.href = "{{ route('students.index') }}";
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
                    errorHtml = `<p>${xhr.responseJSON?.message || 'Failed to add student.'}</p>`;
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