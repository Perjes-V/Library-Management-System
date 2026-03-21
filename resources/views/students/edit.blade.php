@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Edit Student</h3>
                    <a href="{{ route('students.index') }}" class="btn btn-light btn-sm fw-bold">
                        <i class="bi bi-arrow-left-circle me-1"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">

                    {{-- AJAX Messages --}}
                    <div id="ajax-messages"></div>

                    <form id="editStudentForm" action="{{ route('students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" name="student_id" id="student_id" class="form-control"
                                   value="{{ old('student_id', $student->student_id) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $student->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <input type="text" name="course" id="course" class="form-control"
                                   value="{{ old('course', $student->course) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="year_level" class="form-label">Year Level</label>
                            <select name="year_level" id="year_level" class="form-select" required>
                                <option value="">-- Select Year Level --</option>
                                @php
                                    $levels = ['1st', '2nd', '3rd', '4th'];
                                @endphp
                                @foreach($levels as $index => $level)
                                    <option value="{{ $index + 1 }}"
                                        {{ old('year_level', $student->year_level) == ($index + 1) ? 'selected' : '' }}>
                                        {{ $level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Update Student
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
    $('#editStudentForm').on('submit', function(e){
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response){
                // Show success message
                $('#ajax-messages').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${response.message || 'Student updated successfully!'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);

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
                    errorHtml = `<p>${xhr.responseJSON?.message || 'Failed to update student.'}</p>`;
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