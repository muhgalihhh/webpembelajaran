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
            // Tambahkan kolom baru setelah kolom whatsapp_group_id
            $table->string('whatsapp_group_link')->nullable()->after('whatsapp_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('whatsapp_group_link');
        });
    }
};