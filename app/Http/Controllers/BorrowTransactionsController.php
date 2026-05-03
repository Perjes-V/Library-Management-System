<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowTransaction;
use App\Models\Student;
use App\Models\Book;
use App\Models\ReturnedBook;
use Carbon\Carbon;

class BorrowTransactionsController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $records = BorrowTransaction::with(['student', 'book'])->get();
        return view('borrow_transactions.index', compact('records'));
    }

    // ================= CREATE =================
    public function create()
    {
        $students = Student::all();
        $books = Book::all();
        return view('borrow_transactions.create', compact('students', 'books'));
    }

    // ================= STORE (FIXED AJAX) =================
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'books' => 'required|array',
            'books.*' => 'exists:books,id',
            'quantities' => 'required|array'
        ]);

        $studentId = $request->student_id;
        $borrowDate = Carbon::now();
        $dueDate = Carbon::now()->addDays(7);

        foreach ($request->books as $bookId) {

            $quantity = $request->quantities[$bookId] ?? 1;

            $book = Book::find($bookId);
            if (!$book || $book->quantity < $quantity) continue;

            $existing = BorrowTransaction::where('student_id', $studentId)
                ->where('book_id', $bookId)
                ->where('status', 'borrowed')
                ->first();

            if ($existing) continue;

            BorrowTransaction::create([
                'student_id' => $studentId,
                'book_id' => $bookId,
                'borrow_date' => $borrowDate,
                'due_date' => $dueDate,
                'quantity' => $quantity,
                'status' => 'borrowed'
            ]);

            $book->decrement('quantity', $quantity);
        }

        return response()->json([
            'success' => true,
            'message' => 'Books borrowed successfully.'
        ]);
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $record = BorrowTransaction::findOrFail($id);
        $students = Student::all();
        $books = Book::all();

        return view('borrow_transactions.edit', compact('record', 'students', 'books'));
    }

    // ================= UPDATE (FIXED AJAX) =================
    public function update(Request $request, $id)
    {
        $record = BorrowTransaction::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'book_id' => 'required|exists:books,id',
            'status' => 'required|in:borrowed,returned',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
            'return_quantity' => 'nullable|integer|min:1|max:' . $record->quantity
        ]);

        $returnQuantity = $request->return_quantity ?? 0;

        if ($request->status === 'returned' && $returnQuantity > 0) {

            $book = Book::find($record->book_id);

            if ($book) {
                $book->increment('quantity', $returnQuantity);
            }

            ReturnedBook::create([
                'borrow_transaction_id' => $record->id,
                'book_id' => $record->book_id,
                'quantity' => $returnQuantity,
                'return_date' => Carbon::now(),
            ]);

            $record->quantity -= $returnQuantity;

            if ($record->quantity <= 0) {
                $record->delete();
            } else {
                $record->status = 'borrowed';
                $record->save();
            }

        } else {
            $record->status = $request->status;
            $record->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully.'
        ]);
    }

    // ================= DESTROY =================
    public function destroy($id)
    {
        $record = BorrowTransaction::findOrFail($id);

        if ($record->status === 'borrowed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a book that is still borrowed.'
            ]);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully.'
        ]);
    }

    // ================= RETURNED BOOKS =================
    public function returned()
    {
        $returnedBooks = ReturnedBook::with(['borrowTransaction.student', 'book'])
            ->orderBy('return_date', 'desc')
            ->get();

        return view('borrow_transactions.returned', compact('returnedBooks'));
    }

    // ================= DELETE RETURNED =================
    public function destroyReturned($id)
    {
        $returned = ReturnedBook::findOrFail($id);

        $returned->delete();

        return response()->json([
            'success' => true,
            'message' => 'Returned book record deleted successfully.'
        ]);
    }
}