<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat 1 akun Admin/Kasir tetap
        User::create([
            'name'     => 'Anwar Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('123456789'), // Password-nya di-hash resmi
            'role_id'  => 1, // Sesuaikan id role jika ada
        ]);

        // 2. Buat 5 user dummy tambahan dari factory (password-nya default: "password")
        User::factory()->count(5)->create();
    }
}
