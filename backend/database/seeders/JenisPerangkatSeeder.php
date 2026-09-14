<?php

namespace Database\Seeders;

use App\Models\Jenis_perangkat;
use Illuminate\Database\Seeder;

class JenisPerangkatSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Regular', 'harga_per_jam' => 5000],
            ['name' => 'VIP', 'harga_per_jam' => 7000],
            ['name' => 'VVIP', 'harga_per_jam' => 10000],
        ];

        foreach ($data as $item) {
            Jenis_perangkat::updateOrCreate(
                ['name' => $item['name']], // cek berdasarkan kolom unique
                $item
            );
        }
    }
}