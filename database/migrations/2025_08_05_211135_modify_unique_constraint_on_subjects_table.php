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
        Schema::table('subjects', function (Blueprint $table) {

            $table->dropUnique('subjects_name_unique');

            $table->unique(['name', 'kurikulum']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Kembalikan ke kondisi semula jika migrasi di-rollback
            $table->dropUnique(['name', 'kurikulum']);
            $table->unique('name');
        });
    }
};
