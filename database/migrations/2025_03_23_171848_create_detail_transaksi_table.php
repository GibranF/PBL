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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail_transaksi');
            $table->unsignedBigInteger('id_layanan')->nullable();
            $table->string('id_transaksi')->nullable();
            $table->decimal('subtotal', 10,2);
            $table->string('satuan', 10);
            $table->decimal('dimensi', 10,2);
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('id_layanan')->references('id_layanan')->on('layanan')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
