<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('books')) {
            Schema::create('books', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->text('synopsis');
                $table->string('isbn');
                $table->smallInteger('pages_amount');
                $table->date('published_at');
                $table->string('image_url')
                    ->nullable();
                $table->smallInteger('chapters_amount');
                $table->string('slug');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
