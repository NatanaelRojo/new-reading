<?php

use App\Models\API\V1\Book;
use App\Models\API\V1\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('book_tag')) {
            Schema::create('book_tag', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Book::class);
                $table->foreignIdFor(Tag::class);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_tag');
    }
};
