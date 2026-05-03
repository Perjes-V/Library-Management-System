<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Models\Category;

class BooksController extends Controller
{
    // ===================== INDEX =====================
    public function index()
    {
        $books = Book::with('category')->get();

        return request()->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Books loaded',
                'data' => $books
            ])
            : view('books.index', compact('books'));
    }

    // ===================== CREATE =====================
    public function create()
    {
        $categories = Category::all();

        return request()->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Categories loaded',
                'data' => $categories
            ])
            : view('books.create', compact('categories'));
    }

    // ===================== STORE =====================
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'       => 'required|max:255',
                'author'      => 'required|max:255',
                'category_id' => 'required|exists:categories,id',
                'quantity'    => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            $exists = Book::where([
                ['title', $data['title']],
                ['author', $data['author']],
                ['category_id', $data['category_id']],
            ])->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate book found',
                    'errors'  => []
                ], 422);
            }

            $book = Book::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Book added successfully!',
                'data'    => $book->load('category')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred',
                'errors'  => []
            ], 500);
        }
    }

    // ===================== EDIT =====================
    public function edit(Book $book)
    {
        $categories = Category::all();

        return request()->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Book loaded',
                'data' => [
                    'book' => $book,
                    'categories' => $categories
                ]
            ])
            : view('books.edit', compact('book', 'categories'));
    }

    // ===================== UPDATE =====================
    public function update(Request $request, Book $book)
    {
        try {

            $validator = Validator::make($request->all(), [
                'title'       => 'required|max:255',
                'author'      => 'required|max:255',
                'category_id' => 'required|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // duplicate check
            $exists = Book::where([
                ['title', $data['title']],
                ['author', $data['author']],
                ['category_id', $data['category_id']],
            ])
            ->where('id', '!=', $book->id)
            ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate book found',
                    'errors'  => []
                ], 422);
            }

            $book->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully!',
                'data' => $book->fresh('category')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred',
                'errors'  => []
            ], 500);
        }
    }

    // ===================== DELETE =====================
    public function destroy(Book $book)
    {
        try {

            if ($book->borrowTransactions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete book. It has borrow records.',
                    'errors'  => []
                ], 400);
            }

            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully!',
                'data'    => null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred',
                'errors'  => []
            ], 500);
        }
    }
}