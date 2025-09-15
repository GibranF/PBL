<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

        protected $primaryKey = 'id_detail_transaksi';

    // Nama tabel yang sesuai dengan nama tabel di database
    protected $table = 'detail_transaksi';

    // Kolom yang boleh diisi secara mass assignment
    protected $fillable = [
        'id_layanan', 'id_transaksi', 'subtotal', 'satuan', 'dimensi'
    ];

    // Relasi dengan model Transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    // Relasi dengan model Layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }
}
