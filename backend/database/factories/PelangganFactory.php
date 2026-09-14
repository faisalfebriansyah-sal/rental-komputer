<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PelangganFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake('id_ID')->name(),
            'no_hp' => fake()->unique()->numerify('08##########'), // 08 + 10 digit acak
        ];
    }
}