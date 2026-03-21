<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // Allow mass assignment
    protected $fillable = [
        'student_id',  // your student ID column
        'name',
        'course',
        'year_level',
    ];

    // Relationship: if needed
    public function borrowTransactions()
    {
        return $this->hasMany(BorrowTransaction::class);
    }
}