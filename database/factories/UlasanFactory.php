<?php

namespace Database\Factories;

use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UlasanFactory extends Factory
{
    protected $model = Ulasan::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'pesan' => $this->faker->sentence(10),
            'rating' => $this->faker->numberBetween(1, 5),
            'tanggal' => $this->faker->date(),
        ];
    }
}
