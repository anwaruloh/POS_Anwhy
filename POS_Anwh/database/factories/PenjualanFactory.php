<?php

namespace Database\Factories;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends Factory<Penjualan>
 */
class PenjualanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Penjualan::class;
    
    public function definition(): array
    {
        return [
            'user_id' => User::where('role_id', 2)->inRandomOrder()->value('id')
                ?? User::inRandomOrder()->value('id')
                ?? User::factory(),
            'total_pembayaran' => 0, // akan di update di seeder
            'metode_pembayaran' => $this->faker->randomElement(['CASH', 'TRANSFER', 'QRIS']),
            'status' => $this->faker->randomElement(['OPEN', 'COMPLETED']),
        ];
    }
}
