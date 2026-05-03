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
