@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center text-primary fw-bold"><i class="bi bi-bar-chart-fill me-2"></i> Library Admin Dashboard</h2>

    <div class="row g-4">
        <!-- Students Card -->
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100 shadow-lg rounded-4">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title fw-semibold"><i class="bi bi-people-fill me-2"></i> Students</h5>
                        <p class="card-text fs-3 fw-bold">{{ $studentsCount }}</p>
                    </div>
                    <a href="{{ route('students.index') }}" class="btn btn-light fw-bold mt-3">View Students</a>
                </div>
            </div>
        </div>

        <!-- Books Card -->
        <div class="col-md-4">
            <div class="card text-white bg-success h-100 shadow-lg rounded-4">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title fw-semibold"><i class="bi bi-book-fill me-2"></i> Books</h5>
                        <p class="card-text fs-3 fw-bold">{{ $booksCount }}</p>
                    </div>
                    <a href="{{ route('books.index') }}" class="btn btn-light fw-bold mt-3">View Books</a>
                </div>
            </div>
        </div>

        <!-- Borrowed Books Card -->
        <div class="col-md-4">
            <div class="card text-white bg-warning h-100 shadow-lg rounded-4">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title fw-semibold"><i class="bi bi-journal-bookmark-fill me-2"></i> Borrow Transactions</h5>
                        <p class="card-text fs-3 fw-bold">{{ $borrowedCount }}</p>
                    </div>
                    <a href="{{ route('borrow_transactions.index') }}" class="btn btn-light fw-bold mt-3">View Borrow Records</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tagline Below Cards -->
   <div class="row mt-5">
    <div class="col">
        <div class="p-5 shadow-lg rounded-4 text-center" 
             style="background: linear-gradient(135deg, #e61717, #0ba00e, #ffbf0e);
                    animation: fadeIn 1.5s ease-in-out;
                    transition: transform 0.3s;">
            <p class="lead fst-italic text-white fw-bold mb-0" 
               style="font-size: 1.6rem; line-height: 1.8; text-shadow: 1px 1px 3px rgba(0,0,0,0.6);">
                "Step into a world where every book opens a new adventure, every page sparks curiosity, and every visit brings the chance to discover, learn, and grow together"
            </p>
        </div>
    </div>
</div>
</div>
@endsection