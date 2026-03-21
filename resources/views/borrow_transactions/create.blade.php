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

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Enable quantity input only if book is checked
    $('.book-checkbox').change(function(){
        let bookId = $(this).data('id');
        let qtyInput = $(`input[name="quantities[${bookId}]"]`);
        qtyInput.prop('disabled', !this.checked);
        if(!this.checked){
            qtyInput.val(1);
        }
    });

    // Calendar shortcut for Due Date
    $('#due_date_btn').click(function(){
        const dueInput = document.getElementById('return_date');
        if(dueInput.showPicker){  // modern browsers
            dueInput.showPicker();
        } else {                   // fallback for unsupported browsers
            dueInput.focus();
        }
    });

    // Auto-set Borrow Date to today
    const borrowInput = document.getElementById('borrow_date');
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    borrowInput.value = `${yyyy}-${mm}-${dd}`;
    borrowInput.min = `${yyyy}-${mm}-${dd}`; // prevent past dates

    // Auto-set Due Date to 7 days from today
    const dueInput = document.getElementById('return_date');
    const defaultDue = new Date();
    defaultDue.setDate(today.getDate() + 7); // 7 days from today
    const dueY = defaultDue.getFullYear();
    const dueM = String(defaultDue.getMonth() + 1).padStart(2, '0');
    const dueD = String(defaultDue.getDate()).padStart(2, '0');
    dueInput.value = `${dueY}-${dueM}-${dueD}`;
    dueInput.min = `${yyyy}-${mm}-${dd}`; // prevent past dates

    // Handle form submission
    $('#borrowForm').on('submit', function(e){
        e.preventDefault();

        let formData = $(this).serialize(); // works with books[] and quantities[]

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response){
                $('#ajax-messages').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${response.message || 'Books borrowed successfully!'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                // Reset form
                $('#borrowForm')[0].reset();
                $('.quantity-input').prop('disabled', true).val(1);

                // Reset dates
                borrowInput.value = `${yyyy}-${mm}-${dd}`;
                dueInput.value = `${dueY}-${dueM}-${dueD}`;

                // Redirect to borrowed list after 1s
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
                    errorHtml = `<p>${xhr.responseJSON?.message || 'Failed to borrow books.'}</p>`;
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