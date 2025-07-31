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
        Schema::table('classes', function (Blueprint $table) {
            // Kolom 'class' untuk tingkatan (misal: 'I', 'II', 'VI')
            $table->string('class')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('class');
        });
    }
};