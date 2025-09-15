<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';
    

    protected $fillable = [
        'id_user',
        'pesan',
        'rating',
        'tanggal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}