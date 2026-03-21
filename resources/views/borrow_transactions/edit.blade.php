@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Edit Borrow Transaction</h3>
                </div>

                <div class="card-body p-4">
                    {{-- AJAX Messages --}}
                    <div id="ajax-messages"></div>

                    <form id="editBorrowForm" action="{{ route('borrow_transactions.update', $record->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Student -->
                        <div class="mb-3">
                            <label for="student_id" class="form-label fw-semibold">Student</label>
                            <select name="student_id" id="student_id" class="form-select rounded-3" required>
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                        {{ $record->student_id == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->student_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Book -->
                        <div class="mb-3">
                            <label for="book_id" class="form-label fw-semibold">Book</label>
                            <select name="book_id" id="book_id" class="form-select rounded-3" required>
                                <option value="">-- Select Book --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" 
                                        {{ $record->book_id == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }} ({{ $book->author }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Borrow Date -->
                        <div class="mb-3">
                            <label for="borrow_date" class="form-label fw-semibold">Borrow Date</label>
                            <input type="date" name="borrow_date" id="borrow_date" class="form-control rounded-3"
                                value="{{ $record->borrow_date ? \Carbon\Carbon::parse($record->borrow_date)->format('Y-m-d') : '' }}" required>
                        </div>

                        <!-- Due Date -->
                        <div class="mb-3">
                            <label for="due_date" class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control rounded-3"
                                value="{{ $record->due_date ? \Carbon\Carbon::parse($record->due_date)->format('Y-m-d') : '' }}" required>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold">Status</label>
                            <select name="status" id="status" class="form-select rounded-3" required>
                                <option value="borrowed" {{ $record->status == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                <option value="returned" {{ $record->status == 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>

                        <!-- Return Quantity -->
                        <div class="mb-3" id="returnQuantityContainer" style="display: none;">
                            <label for="return_quantity" class="form-label fw-semibold">Return Quantity</label>
                            <input type="number" name="return_quantity" id="return_quantity" class="form-control rounded-3"
                                min="1" max="{{ $record->quantity }}" value="1">
                            <small class="text-muted">Max: {{ $record->quantity }}</small>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold">
                                <i class="bi bi-save-fill me-2"></i> Update Transaction
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

    // Show/hide Return Quantity based on Status
    $('#status').change(function() {
        if ($(this).val() === 'returned') {
            $('#returnQuantityContainer').show();
            $('#return_quantity').val(1);
        } else {
            $('#returnQuantityContainer').hide();
            $('#return_quantity').val(1);
        }
    });

    // ================= AJAX UPDATE =================
    $('#editBorrowForm').on('submit', function(e){
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response){
                $('#ajax-messages').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${response.message || 'Transaction updated successfully!'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                setTimeout(() => {
                    window.location.href = "{{ route('borrow_transactions.index') }}";
                }, 1000);
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
                    errorHtml = `<p>${xhr.responseJSON?.message || 'Failed to update transaction.'}</p>`;
                }

                $('#ajax-messages').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${errorHtml}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            }
        });
    });
});
</script>
@endsection