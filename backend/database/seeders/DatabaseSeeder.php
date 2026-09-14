<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            JenisPerangkatSeeder::class,
            PerangkatSeeder::class,
            PelangganSeeder::class,
            AdminSeeder::class,
        ]);
    }
}

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Membuat Data Admin...⏳⌛');
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info('Admin Berhasil Dibuat! 🎉✔');
    }
}