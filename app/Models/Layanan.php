<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    public $incrementing = true;
    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nama_layanan',
        'harga',
        'deskripsi',
        'satuan',
        'gambar',
    ];

    // Relasi ke detail_transaksi
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_layanan', 'id_layanan');
    }
}
