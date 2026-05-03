@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3 class="mb-0"><i class="bi bi-book-fill me-2"></i> Borrow Book</h3>
                </div>

                <div class="card-body p-4">
                    {{-- AJAX Messages --}}
                    <div id="ajax-messages"></div>

                    <form id="borrowForm" action="{{ route('borrow_transactions.store') }}" method="POST">
                        @csrf

                        <!-- Student Select -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Student</label>
                            <select name="student_id" id="student_id" class="form-select rounded-3" required>
                                <option value="">-- Choose Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Books Table -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Books</label>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover text-center align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Select</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Available Copies</th>
                                            <th>Quantity to Borrow</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($books as $book)
                                            <tr>
                                                <td>
                                                    @if($book->quantity > 0)
                                                        <input type="checkbox" class="book-checkbox" data-id="{{ $book->id }}" name="books[]" value="{{ $book->id }}">
                                                    @else
                                                        <span class="badge bg-danger">Out of Stock</span>
                                                    @endif
                                                </td>
                                                <td>{{ $book->title }}</td>
                                                <td>{{ $book->author }}</td>
                                                <td>{{ $book->quantity }}</td>
                                                <td>
                                                    @if($book->quantity > 0)
                                                        <input type="number" name="quantities[{{ $book->id }}]" class="form-control form-control-sm quantity-input" min="1" max="{{ $book->quantity }}" value="1" disabled>
                                                    @else
                                                        <input type="number" class="form-control form-control-sm" disabled>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Borrow Date -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Borrow Date</label>
                            <input type="date" name="borrow_date" id="borrow_date" class="form-control rounded-3" required>
                        </div>

                        <!-- Due Date with Calendar Shortcut -->
                        <div class="mb-3 position-relative">
                            <label class="form-label fw-semibold">Due Date</label>
                            <div class="input-group">
                                <input type="date" name="return_date" id="return_date" class="form-control rounded-3" required>
                                <button type="button" class="btn btn-outline-secondary" id="due_date_btn" title="Pick a date">
                                    <i class="bi bi-calendar3"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                                <i class="bi bi-arrow-right-circle-fill me-2"></i> Borrow Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
