<?php

namespace Database\Factories;

use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayananFactory extends Factory
{
    protected $model = Layanan::class;

    public function definition()
    {
        return [
            'nama_layanan' => $this->faker->word(),           // nama layanan acak
            'harga' => $this->faker->numberBetween(1000, 50000), // harga acak
            'deskripsi' => $this->faker->sentence(),          // deskripsi acak
            'gambar' => 'default.png',                        // bisa default atau generate faker image
        ];
    }
}
