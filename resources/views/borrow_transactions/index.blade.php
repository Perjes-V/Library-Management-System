@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="bi bi-journal-bookmark-fill me-2"></i> Borrowed Books Masterlist</h3>
                    <a href="{{ route('borrow_transactions.create') }}" class="btn btn-light btn-sm fw-bold">
                        <i class="bi bi-plus-circle-fill me-1"></i> Borrow Book
                    </a>
                </div>

                <div class="card-body p-4">
                    {{-- AJAX Messages --}}
                    <div id="ajax-messages"></div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle text-center" id="borrowedBooksTable">
                            
                            <thead class="table-dark">
                                <tr>
                                    <th>Student</th>
                                    <th>Book</th>
                                    <th>Quantity</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($records as $tx)

                                    <tr id="tx-{{ $tx->id }}">
                                        {{-- Student --}}
                                        <td>{{ $tx->student?->name ?? 'Student not found' }} ({{ $tx->student?->student_id ?? '-' }})</td>

                                        {{-- Book Title --}}
                                        <td>{{ $tx->book?->title ?? 'Book not found' }}</td>

                                        {{-- Quantity --}}
                                        <td>{{ $tx->quantity }}</td>

                                        {{-- Borrow Date --}}
                                        <td>{{ $tx->borrow_date ? \Carbon\Carbon::parse($tx->borrow_date)->format('M d, Y') : '-' }}</td>

                                        {{-- Due Date --}}
                                        <td>{{ $tx->due_date ? \Carbon\Carbon::parse($tx->due_date)->format('M d, Y') : '-' }}</td>

                                        {{-- Status --}}
                                        <td>
                                            @if($tx->status == 'borrowed')
                                                <span class="badge bg-primary">Borrowed</span>
                                            @elseif($tx->status == 'returned')
                                                <span class="badge bg-success">Returned</span>
                                            @else
                                                <span class="badge bg-danger">Overdue</span>
                                            @endif
                                        </td>
                                        
                                        {{-- Actions --}}
                                        <td>
                                            <a href="{{ route('borrow_transactions.edit', $tx->id) }}" class="btn btn-sm btn-warning me-1">
                                                <i class="bi bi-pencil-fill"></i> Edit
                                            </a>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No borrowed books found</td>
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
