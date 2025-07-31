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
        Schema::table('tasks', function (Blueprint $table) {
            // Menambahkan relasi ke tabel kelas, setelah subject_id
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null')->after('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};