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
        $table->string('gambar')->nullable(); // tambahkan ini
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
