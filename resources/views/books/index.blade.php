@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-book-half me-2"></i> Books Masterlist</h3>
                    <a href="{{ route('books.create') }}" class="btn btn-light btn-sm fw-bold">
                        <i class="bi bi-plus-circle-fill me-1"></i> Add Book
                    </a>
                </div>

                <div class="card-body p-4">
                    {{-- AJAX Messages --}}
                    <div id="ajax-messages"></div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle text-center" id="booksTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $book)
                                    <tr id="row_{{ $book->id }}">
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->author }}</td>
                                        <td>{{ $book->category?->name ?? '-' }}</td>
                                        <td>{{ $book->quantity }}</td>
                                        <td>
                                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-warning me-1">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>

                                            <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $book->id }}">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No books found</td>
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

    // ================= DELETE BOOK =================
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id');
        if(confirm("Delete this book?")){
            $.ajax({
                url: '/books/' + id,
                type: 'DELETE',
                success: function(response){
                    $('#row_' + id).remove();
                    let alertHtml = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                        ${response.message || 'Book deleted successfully!'}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                     </div>`;
                    $('.container').first().prepend(alertHtml);
                    setTimeout(() => { $('.alert').alert('close'); }, 3000);
                },
                error: function(){
                    alert('Failed to delete the book.');
                }
            });
        }
    });
});
</script>
@endsection