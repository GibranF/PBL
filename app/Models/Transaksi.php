<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // Nama tabel yang sesuai dengan nama tabel di database
    protected $table = 'transaksi';

 protected $primaryKey = 'id_transaksi';
public $incrementing = false;
protected $keyType = 'string';

protected $casts = [
        'tanggal' => 'datetime',
    ];

protected $fillable = [
    'id_transaksi', 'id_user', 'nama_pelanggan', 'alamat', 'nomor_hp',
    'status', 'tanggal', 'biaya_antar', 'jarak_km', 'total',
    'tanggal_pembayaran', 'status_pembayaran', 'metode_pembayaran', 'snap_token'
];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi dengan model DetailTransaksi
     public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

   protected static function boot()
    {
        parent::boot();

        static::deleting(function ($transaksi) {
            // Hapus data detail transaksi saat transaksi dihapus
            $transaksi->detailTransaksi()->delete();
        });
    }
}
