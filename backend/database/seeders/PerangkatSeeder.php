<?php

namespace Database\Seeders;

use App\Models\Perangkat;
use Illuminate\Database\Seeder;

class PerangkatSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'PC 1', 'jenis_id' => 1, 'status' => 'tersedia'],
            ['name' => 'PC 2', 'jenis_id' => 1, 'status' => 'tersedia'],
            ['name' => 'PC 3', 'jenis_id' => 1, 'status' => 'tersedia'],
            ['name' => 'PC 4', 'jenis_id' => 1, 'status' => 'tersedia'],
            ['name' => 'PC 5', 'jenis_id' => 1, 'status' => 'tersedia'],
            ['name' => 'PC 6', 'jenis_id' => 2, 'status' => 'tersedia'],
            ['name' => 'PC 7', 'jenis_id' => 2, 'status' => 'tersedia'],
            ['name' => 'PC 8', 'jenis_id' => 2, 'status' => 'tersedia'],
            ['name' => 'PC 9', 'jenis_id' => 3, 'status' => 'tersedia'],
            ['name' => 'PC 10', 'jenis_id' => 3, 'status' => 'tersedia'],
        ];

        foreach ($data as $item) {
            Perangkat::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}