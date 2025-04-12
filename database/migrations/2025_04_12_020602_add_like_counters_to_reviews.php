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
        if (!Schema::hasColumn('reviews', 'like_count')
            && !Schema::hasColumn('reviews', 'dislike_count')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unsignedInteger('like_count')->default(0);
                $table->unsignedInteger('dislike_count')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'like_count',
                'dislike_count',
            ]);
        });
    }
};
