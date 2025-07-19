<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database/migrations/..._add_columns_to_users_table.php
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null'); // Hanya untuk siswa
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('profile_picture')->nullable();
            // Spatie HasRoles trait akan mengelola role_id melalui tabel pivot
        });
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
