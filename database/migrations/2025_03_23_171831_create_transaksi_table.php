<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('id_transaksi')->primary(); 
            $table->unsignedBigInteger('id_user');
            $table->string('nama_pelanggan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->enum('status', ['belum diproses', 'pesanan diproses', 'pesanan selesai'])->default('belum diproses');
            $table->dateTime('tanggal');
            $table->decimal('biaya_antar', 15, 2)->default(0)->nullable();
            $table->decimal('total', 15, 2);
            $table->decimal('jarak_km', 8, 2)->nullable();
            $table->dateTime('tanggal_pembayaran')->nullable();
            $table->enum('status_pembayaran',['belum dibayar', 'sudah dibayar'])->default('belum dibayar');
            $table->string('metode_pembayaran')->nullable();
            $table->string('snap_token')->nullable();
            $table->foreign('id_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};