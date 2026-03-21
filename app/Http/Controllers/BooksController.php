<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class BooksController extends Controller
{
    // ===================== INDEX =====================
    public function index(Request $request)
    {
        $books = Book::with('category')->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $books
            ]);
        }

        return view('books.index', compact('books'));
    }

    // ===================== CREATE =====================
    public function create(Request $request)
    {
        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        }

        return view('books.create', compact('categories'));
    }

    // ===================== STORE =====================
    public function store(Request $request)
{
    $request->validate([
        'title'       => 'required|max:255',
        'author'      => 'required|max:255',
        'category_id' => 'required|exists:categories,id',
        'quantity'    => 'required|integer|min:1', // new validation
    ]);

    // Check for duplicate
    $exists = Book::where('title', $request->title)
        ->where('author', $request->author)
        ->where('category_id', $request->category_id)
        ->exists();

    if ($exists) {
        $error = ['duplicate' => ['Another book with the same title, author, and category already exists.']];

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'errors' => $error
            ], 422);
        }

        return back()->withErrors($error)->withInput();
    }

    // Create book with quantity
    $book = Book::create([
        'title'       => $request->title,
        'author'      => $request->author,
        'category_id' => $request->category_id,
        'quantity'    => $request->quantity, // set initial quantity
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Book added successfully!',
            'data' => $book->load('category')
        ]);
    }

    return redirect()->route('books.index')
                     ->with('success', 'Book added successfully!');
}

    // ===================== EDIT =====================
    public function edit(Request $request, Book $book)
    {
        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'book' => $book,
                    'categories' => $categories
                ]
            ]);
        }

        return view('books.edit', compact('book', 'categories'));
    }

    // ===================== UPDATE =====================
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'author'      => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Check for duplicate excluding current book
        $exists = Book::where('title', $request->title)
            ->where('author', $request->author)
            ->where('category_id', $request->category_id)
            ->where('id', '!=', $book->id)
            ->exists();

        if ($exists) {
            $error = ['duplicate' => ['Another book with the same title, author, and category already exists.']];

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $error
                ], 422);
            }

            return back()->withErrors($error)->withInput();
        }

        $book->update([
            'title'       => $request->title,
            'author'      => $request->author,
            'category_id' => $request->category_id,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully!',
                'data' => $book->load('category')
            ]);
        }

        return redirect()->route('books.index')
                         ->with('success', 'Book updated successfully!');
    }

    // ===================== DELETE =====================
    public function destroy(Request $request, Book $book)
    {
        $book->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully!'
            ]);
        }

        return redirect()->route('books.index')
                         ->with('success', 'Book deleted successfully!');
    }
}