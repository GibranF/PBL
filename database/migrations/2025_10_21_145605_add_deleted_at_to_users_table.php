<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom 'deleted_at'.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom ini akan menyimpan timestamp saat user di-soft delete.
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom 'deleted_at' hanya jika kolom tersebut ada.
     */
    public function down(): void
    {
        // **PERBAIKAN:** Cek apakah kolom deleted_at ada sebelum mencoba menghapusnya.
        // Pengecekan ini mencegah error "Can't DROP COLUMN" saat rollback.
        if (Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                // DropSoftDeletes adalah helper untuk menghapus kolom yang dibuat oleh softDeletes().
                $table->dropSoftDeletes();
            });
        }
    }
};
