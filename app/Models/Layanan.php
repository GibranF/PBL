<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nama_layanan',
        'harga',
        'deskripsi',
        'gambar'
    ];
}
