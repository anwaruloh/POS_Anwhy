<?php

namespace Tests\Feature;

use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemPenjualanQtyUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_qty_from_qty_field_and_reduces_stock(): void
    {
        $role = Role::create(['name' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $product = Produk::factory()->create([
            'user_id' => $user->id,
            'nama' => 'Keyboard',
            'harga_jual' => 50_000,
            'stok' => 10,
        ]);

        $sale = Penjualan::create([
            'user_id' => $user->id,
            'total_pembayaran' => 100_000,
            'metode_pembayaran' => 'CASH',
            'status' => 'OPEN',
        ]);

        $item = ItemPenjualan::create([
            'penjualan_id' => $sale->id,
            'produk_id' => $product->id,
            'kuantitas' => 2,
            'harga_satuan' => 50_000,
            'subtotal' => 100_000,
        ]);

        $this->actingAs($user)
            ->from('/penjualan/create')
            ->put(route('itempenjualan.update', $item->id), [
                'qty' => 5,
                '_token' => csrf_token(),
            ]);

        $item->refresh();
        $product->refresh();

        $this->assertSame(5, $item->kuantitas);
        $this->assertSame(7, $product->stok);
        $this->assertSame(250_000, $item->subtotal);
    }

    public function test_it_applies_a_ten_percent_discount_when_transaction_exceeds_one_million(): void
    {
        $role = Role::create(['name' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $product = Produk::factory()->create([
            'user_id' => $user->id,
            'nama' => 'Monitor',
            'harga_jual' => 600_000,
            'stok' => 5,
        ]);

        $sale = Penjualan::create([
            'user_id' => $user->id,
            'total_pembayaran' => 0,
            'metode_pembayaran' => 'CASH',
            'status' => 'OPEN',
        ]);

        $item = ItemPenjualan::create([
            'penjualan_id' => $sale->id,
            'produk_id' => $product->id,
            'kuantitas' => 2,
            'harga_satuan' => 600_000,
            'subtotal' => 1_200_000,
        ]);

        $this->actingAs($user)
            ->from('/penjualan/create')
            ->put(route('itempenjualan.update', $item->id), [
                'qty' => 2,
                '_token' => csrf_token(),
            ]);

        $sale->refresh();

        $this->assertSame(1_200_000, $item->fresh()->subtotal);
        $this->assertSame(1_080_000, $sale->total_pembayaran);
    }
}
