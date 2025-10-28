<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    /**
     * Tentukan kolom yang digunakan untuk login.
     *
     * @param  string  $login
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findForLogin($login)
    {
        // Periksa apakah login adalah email atau username
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return $this->where('email', $login)->first();
        }
        return $this->where('name', $login)->first();
    }
    protected $fillable = [
        'name',
        'email',
        'profile_photo',
        'user_type',
        'alamat',
        'nomor_hp',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

     protected static function boot()
    {
        parent::boot();

        // Ketika event 'forceDeleting' dipanggil (penghapusan permanen), 
        // kita hapus file terkait dari storage.
        static::forceDeleting(function ($user) {
            
            // Hapus Foto Profil dari storage
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // Catatan: Data transaksi (Orders, dll) tetap aman di database
            // karena ini adalah Soft Delete, bukan penghapusan permanen.
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
       ];
}

}
