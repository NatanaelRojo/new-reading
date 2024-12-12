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
        if (Schema::hasTable('users')
        && !Schema::hasColumn('users', 'profile_image_url')
        && !Schema::hasColumn('users', 'first_name')
        && !Schema::hasColumn('users', 'last_name')
        && !Schema::hasColumn('users', 'full_name')
        && !Schema::hasColumn('users', 'birth_date')
        && !Schema::hasColumn('users', 'description')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_image_url')
                ->nullable();
                $table->string('first_name')
                    ->default('');
                $table->string('last_name')
                    ->default('');
                $table->string('full_name')
                    ->storedAs("first_name || ' ' || last_name");
                $table->date('birth_date')
                    ->nullable();
                $table->text('description')
                    ->default('')
                    ->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
