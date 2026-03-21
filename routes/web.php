<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\BorrowTransactionsController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/profile', [ProfileController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');


// ===================== STUDENTS =====================
Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentsController::class, 'create'])->name('students.create');
Route::post('/students', [StudentsController::class, 'store'])->name('students.store');
Route::get('/students/{id}/edit', [StudentsController::class, 'edit'])->name('students.edit');
Route::put('/students/{id}', [StudentsController::class, 'update'])->name('students.update');
Route::delete('/students/{id}', [StudentsController::class, 'destroy'])->name('students.destroy');


// ===================== BOOKS =====================
Route::get('/books', [BooksController::class, 'index'])->name('books.index');
Route::get('/books/create', [BooksController::class, 'create'])->name('books.create');
Route::post('/books', [BooksController::class, 'store'])->name('books.store');
Route::get('/books/{book}/edit', [BooksController::class, 'edit'])->name('books.edit');
Route::put('/books/{book}', [BooksController::class, 'update'])->name('books.update');
Route::delete('/books/{book}', [BooksController::class, 'destroy'])->name('books.destroy');


// ===================== BORROW TRANSACTIONS =====================
Route::get('/borrow_transactions', [BorrowTransactionsController::class, 'index'])->name('borrow_transactions.index');
Route::get('/borrow_transactions/create', [BorrowTransactionsController::class, 'create'])->name('borrow_transactions.create');
Route::post('/borrow_transactions', [BorrowTransactionsController::class, 'store'])->name('borrow_transactions.store');
Route::get('/borrow_transactions/{id}/edit', [BorrowTransactionsController::class, 'edit'])->name('borrow_transactions.edit');
Route::put('/borrow_transactions/{id}', [BorrowTransactionsController::class, 'update'])->name('borrow_transactions.update');
Route::delete('/borrow_transactions/{id}', [BorrowTransactionsController::class, 'destroy'])->name('borrow_transactions.destroy');
Route::get('/borrow-transactions/returned', [BorrowTransactionsController::class, 'returned'])->name('borrow_transactions.returned');
Route::delete('returned-books/{id}', [BorrowTransactionsController::class, 'destroyReturned'])->name('returned_books.destroy');


// ===================== CATEGORIES =====================
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');