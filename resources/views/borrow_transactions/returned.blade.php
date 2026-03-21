@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center text-primary fw-bold">
        <i class="bi bi-arrow-up-square me-2"></i> Returned Books
    </h2>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            @if($returnedBooks->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Returned ID</th>
                            <th>Student</th>
                            <th>Book</th>
                            <th>Quantity</th>
                            <th>Return Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach($returnedBooks as $index => $returned)
                        <tr id="row-{{ $returned->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $returned->borrowTransaction->student->name ?? 'N/A' }}</td>
                            <td>{{ $returned->book->title ?? 'N/A' }}</td>
                            <td>{{ $returned->quantity }}</td>
                            <td>{{ \Carbon\Carbon::parse($returned->return_date)->format('Y-m-d') }}</td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-returned" data-id="{{ $returned->id }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-warning text-center">
                No returned books found.
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-returned').forEach(button => {
    button.addEventListener('click', function() {
        if(!confirm('Are you sure you want to delete this returned record?')) return;

        let id = this.dataset.id;

        fetch(`/returned-books/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                document.getElementById(`row-${id}`).remove();
                alert(data.message);
            } else {
                alert('Failed to delete record.');
            }
        })
        .catch(err => console.error(err));
    });
});
</script>
@endsection