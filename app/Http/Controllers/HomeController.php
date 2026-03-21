<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Book;
use App\Models\BorrowTransaction;

class HomeController extends Controller
{
    public function dashboard()
    {
        // Count records
        $studentsCount = Student::count();
        $booksCount = Book::count();
        $borrowedCount = BorrowTransaction::where('status', 'borrowed')->count();

        // Pass variables to the view
        return view('dashboard', compact('studentsCount', 'booksCount', 'borrowedCount'));
    }
}