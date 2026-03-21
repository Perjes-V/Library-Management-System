<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('returned_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_transaction_id')->constrained('borrow_transactions')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->integer('quantity')->default(1); 
            $table->date('return_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returned_books');
    }
};