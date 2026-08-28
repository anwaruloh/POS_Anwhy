<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Jalankan semua seeder sesuai urutan relasi
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            // ProdukSeeder::class,
            // PenjualanSeeder::class,
        ]);

        // PERBAIKAN: Berikan role_id pada test user agar tidak terbentur constraint
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => Role::inRandomOrder()->value('id') ?? 1,
        ]);
    }
}
