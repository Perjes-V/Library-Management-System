<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowTransaction extends Model
{
    protected $fillable = [
        'student_id',
        'book_id',
        'quantity',   // ✅ add this
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    // Automatically cast date fields to Carbon instances
    protected $casts = [
        'borrow_date' => 'datetime',
        'due_date'    => 'datetime',
        'return_date' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}