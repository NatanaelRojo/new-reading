<?php

use App\Models\API\V1\Book;
use App\Models\API\V1\Genre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('book_genre')) {
            Schema::create('book_genre', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Book::class);
                $table->foreignIdFor(Genre::class);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_genre');
    }
};
