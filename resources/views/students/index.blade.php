@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-people me-2"></i> Students Masterlist</h3>
                    <a href="{{ route('students.create') }}" class="btn btn-light btn-sm fw-bold">
                        <i class="bi bi-person-plus-fill me-1"></i> Add Student
                    </a>
                </div>

                <div class="card-body p-4">

                    {{-- AJAX Messages --}}
                    <div id="ajax-messages">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Year Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr id="row_{{ $student->id }}">
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->course }}</td>
                                        <td>{{ $student->year_level }}</td>
                                        <td>
                                            <!-- Edit Button -->
                                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>

                                            <!-- AJAX Delete Button -->
                                            <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $student->id }}">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No students found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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

    // ================= CSRF SETUP =================
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ================= DELETE STUDENT =================
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id');

        if(confirm("Are you sure you want to delete this student?")){
            $.ajax({
                url: '/students/' + id,
                type: 'DELETE',
                success: function(response){
                    // Remove the row from table
                    $('#row_' + id).remove();

                    // Show success alert
                    let alertHtml = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        ${response.message || 'Student deleted successfully!'}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                     </div>`;
                    $('#ajax-messages').html(alertHtml);
                    setTimeout(() => { $('.alert').alert('close'); }, 3000);
                },
                error: function(xhr){
                    let msg = xhr.responseJSON?.message || 'Failed to delete the student.';
                    let alertHtml = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${msg}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                     </div>`;
                    $('#ajax-messages').html(alertHtml);
                    setTimeout(() => { $('.alert').alert('close'); }, 4000);
                }
            });
        }
    });

});
</script>
@endsection