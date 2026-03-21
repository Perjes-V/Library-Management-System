<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Allow mass assignment
    protected $fillable = [
        'name',
    ];

    // Relationship: One Category has many Books
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}