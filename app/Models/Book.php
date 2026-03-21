<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'category_id',
        'quantity', 
    ];

    // Relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with borrow transactions
    public function borrowTransactions()
    {
        return $this->hasMany(BorrowTransaction::class);
    }   
}