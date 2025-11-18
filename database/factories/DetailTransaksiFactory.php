<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Layanan;

class DetailTransaksiFactory extends Factory
{
    protected $model = DetailTransaksi::class;

    public function definition()
    {
        return [
            'id_transaksi' => Transaksi::factory(), // buat transaksi baru
            'id_layanan' => Layanan::factory(),     // buat layanan baru
            'subtotal' => $this->faker->numberBetween(5000, 200000),
            'satuan' => $this->faker->randomElement(['pcs', 'kg', 'm']), // string
            'dimensi' => $this->faker->randomFloat(2, 1, 10), // angka decimal 1.00 sampai 10.00
        ];
    }
}
