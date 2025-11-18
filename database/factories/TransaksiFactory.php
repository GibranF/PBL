<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Str;

class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition()
    {
        return [
            'id_transaksi' => (string) Str::uuid(),

            // Buat user baru otomatis setiap transaksi
            'id_user' => User::factory(), 

            'nama_pelanggan' => $this->faker->name(),
            'nama_kasir' => $this->faker->name(),
            'alamat' => $this->faker->address(),
            'nomor_hp' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['belum diproses', 'pesanan diproses', 'pesanan selesai']),
            'tanggal' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
            'biaya_antar' => $this->faker->randomFloat(2, 0, 50000),
            'jarak_km' => $this->faker->randomFloat(2, 0, 20),
            'total' => $this->faker->numberBetween(10000, 500000),
            'tanggal_pembayaran' => $this->faker->optional()->dateTimeThisMonth()?->format('Y-m-d H:i:s'),
            'status_pembayaran' => $this->faker->randomElement(['belum dibayar','sudah dibayar']),
            'metode_pembayaran' => $this->faker->randomElement(['cash','gopay','ovo','qris']),
            'snap_token' => null,
        ];
    }
}
