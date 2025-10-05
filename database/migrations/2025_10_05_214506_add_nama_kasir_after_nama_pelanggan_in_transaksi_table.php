<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('nama_kasir')->nullable()->after('nama_pelanggan');
        });
    }

    /**
     * Balik perubahan (rollback).
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('nama_kasir');
        });
    }
};
