<?php

namespace Tests\Feature;

use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeuntunganTest extends TestCase
{
  use RefreshDatabase;

  public function test_admin_can_view_keuntungan_page_and_calculate_profit(): void
  {
    $role = Role::create(['name' => 'admin']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $product = Produk::factory()->create([
      'user_id' => $user->id,
      'nama' => 'Keyboard',
      'harga_beli' => 25_000,
      'harga_jual' => 45_000,
      'stok' => 10,
    ]);

    $sale = Penjualan::create([
      'user_id' => $user->id,
      'total_pembayaran' => 90_000,
      'metode_pembayaran' => 'CASH',
      'status' => 'COMPLETED',
    ]);

    ItemPenjualan::create([
      'penjualan_id' => $sale->id,
      'produk_id' => $product->id,
      'kuantitas' => 2,
      'harga_satuan' => 45_000,
      'subtotal' => 90_000,
    ]);

    $response = $this->actingAs($user)->get(route('admin.keuntungan.index'));

    $response->assertOk();
    $response->assertSee('Riwayat Keuntungan');
    $response->assertSee('TRX-1');
    $response->assertSee('Rp 40.000');
  }
}
