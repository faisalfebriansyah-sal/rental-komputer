<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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