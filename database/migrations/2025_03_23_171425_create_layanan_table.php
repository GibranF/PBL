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
        Schema::create('layanan', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->string('nama_layanan', 25);
            $table->decimal('harga', 10, 2);
            $table->text('deskripsi');
            $table->string('satuan', 50)->nullable(); // bisa diisi 'kg', 'pcs', 'per set', dsb
            $table->string('gambar')->nullable(); // untuk simpan path gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
