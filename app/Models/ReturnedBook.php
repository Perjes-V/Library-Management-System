<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnedBook extends Model
{
    protected $fillable = [
        'borrow_transaction_id',
        'book_id',
        'quantity',
        'return_date',
    ];

    public function borrowTransaction()
    {
        return $this->belongsTo(BorrowTransaction::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}