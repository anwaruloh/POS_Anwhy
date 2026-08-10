<?php

namespace App\Http\Controllers;

use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 1. Ambil atau Buat Transaksi 'OPEN' otomatis jika belum ada
                $sale = Penjualan::firstOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'status'  => 'OPEN'
                    ],
                    [
                        'total_pembayaran' => 0,
                        'metode_pembayaran' => 'CASH'
                    ]
                );

                // 2. Lock data produk untuk cegah race condition
                $product = Produk::lockForUpdate()->findOrFail($request->product_id);

                // 3. Cek stok - Lempar Exception jika tidak cukup agar transaksi di-rollback
                if ($product->stok < $request->quantity) {
                    throw new \Exception('Stok produk ' . $product->nama . ' tidak mencukupi (sisa: ' . $product->stok . ').');
                }

                // 4. Kurangi stok produk
                $product->decrement('stok', $request->quantity);

                // 5. Cek apakah item sudah ada di keranjang
                $item = ItemPenjualan::where('penjualan_id', $sale->id)
                    ->where('produk_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                // Tentukan harga satuan (gunakan harga_jual atau harga)
                $hargaSatuan = $product->harga_jual ?? $product->harga ?? 0;

                if ($item) {
                    // UPDATE
                    $item->kuantitas += $request->quantity;
                } else {
                    // CREATE
                    $item = new ItemPenjualan([
                        'penjualan_id' => $sale->id,
                        'produk_id'    => $product->id,
                        'harga_satuan' => $hargaSatuan,
                        'kuantitas'    => $request->quantity,
                    ]);
                }

                // Hitung subtotal & simpan item
                $item->subtotal = $item->kuantitas * $item->harga_satuan;
                $item->save();

                // 6. Hitung ulang Total Pembayaran Penjualan secara akurat
                $totalPembayaran = ItemPenjualan::where('penjualan_id', $sale->id)->sum('subtotal');
                $sale->update([
                    'total_pembayaran' => $totalPembayaran
                ]);
            });

            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            // Tangkap pesan error stok/lainnya dan kirim ke tampilan
            return back()->with('errors', $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemPenjualan $itempenjualan)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request, $itempenjualan) {

            $produk = $itempenjualan->produk()->lockForUpdate()->first();

            $selisih = $request->quantity - $itempenjualan->kuantitas;

            // 🔍 Jika qty bertambah -> kurangi stok
            if ($selisih > 0) {
                if ($produk->stok < $selisih) {
                    return redirect()->route('penjualan.create')->with('errors', 'Stok tidak mencukupi');
                }
                $produk->decrement('stok', $selisih);
            }

            // 🔍 Jika qty berkurang -> kembalikan stok
            if ($selisih < 0) {
                $produk->increment('stok', abs($selisih));
            }

            // 🔄 Update item
            $itempenjualan->update([
                'kuantitas' => $request->quantity,
                'subtotal' => $request->quantity * $itempenjualan->harga_satuan
            ]);

            // 🔄 Update total penjualan
            $itempenjualan->penjualan->update([
                'total_pembayaran' =>
                $itempenjualan->penjualan->itemPenjualan()->sum('subtotal')
            ]);
        });

        return back();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // 1. Cari item yang akan dihapus beserta relasi produk & penjuualannya
                $item = ItemPenjualan::findOrFail($id);
                $sale = $item->penjualan;

                // 2. Kembalikan stok ke tabel Produk
                if ($item->produk) {
                    $product = Produk::lockForUpdate()->find($item->produk_id);
                    if ($product) {
                        $product->increment('stok', $item->kuantitas);
                    }
                }

                // 3. Hapus item dari database
                $item->delete();

                // 4. Hitung ulang total pembayaran pada transaksi
                $totalPembayaran = ItemPenjualan::where('penjualan_id', $sale->id)->sum('subtotal');
                $sale->update([
                    'total_pembayaran' => $totalPembayaran
                ]);
            });

            return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        } catch (\Exception $e) {
            return back()->with('errors', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
