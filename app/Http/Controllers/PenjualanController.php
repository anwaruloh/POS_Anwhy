<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $user = Auth::user();
        $keyword = $request->input('search');

        $sales = Penjualan::query()
            // 🔓 UBAH DI SINI: Izinkan status COMPLETED, DRAFT, dan OPEN untuk tampil
            ->whereIn('status', ['COMPLETED', 'DRAFT', 'OPEN'])

            // 🔒 Filter berdasarkan role
            ->when($user->role->name === 'kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })

            // 🔍 Search nama user
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penjualan.index', compact('sales'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(SearchRequest $request)
    {
        // 1. Cari transaksi 'OPEN' aktif milik kasir yang sedang login
        $sale = Penjualan::where('user_id', Auth::id())
            ->where('status', 'OPEN')
            ->latest()
            ->first();

        // 2. Ambil parameter filter dari request AJAX / HTTP
        $keyword = $request->input('search');
        $kategoriId = $request->input('kategori_id');

        // 3. Query produk + Eager Loading relasi kategori + Filter Search & Kategori
        $products = Produk::with('kategori')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', '%' . $keyword . '%');
            })
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->orderBy('nama')
            ->get();

        // 4. Ambil semua data kategori untuk dropdown filter di Blade
        $categories = \App\Models\Kategori::orderBy('nama_kategori')->get();

        // 5. Jika request via AJAX (saat kasir mengetik / memilih filter kategori)
        if ($request->ajax()) {
            return view('penjualan.partials.produk_list', compact('products'))->render();
        }

        $mode = 'create';

        // 6. Tampilkan halaman POS utama
        return view('penjualan.pos', compact('sale', 'products', 'categories', 'mode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        // Memuat relasi user (kasir) dan itemPenjualan beserta data produknya
        $penjualan->load(['user', 'itemPenjualan.produk']);

        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        $sale = $penjualan;

        abort_if($sale->status === 'COMPLETED', 403);

        $sale->load('itemPenjualan');
        $products = Produk::with('kategori')->orderBy('nama')->get(); // 1. (Opsional) Tambahkan with('kategori')

        // 2. TAMBAHKAN BARIS INI: Ambil data kategori
        $categories = \App\Models\Kategori::orderBy('nama_kategori')->get();

        $mode = 'edit';

        // 3. UBAH DI SINI: Tambahkan 'categories' ke dalam compact()
        return view('penjualan.pos', compact('sale', 'products', 'categories', 'mode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sale = Penjualan::findOrFail($id);
        $action = $request->input('action');

        // jika tombol draft yang diklik
        if ($action === 'draft') {
            $sale->update([
                'status' => 'DRAFT', // atau 'OPEN' jika enum database Anda belum diupdate ke DRAFT
                'metode_pembayaran' => $request->payment_method ?? 'CASH'
            ]);

            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil disimpan sebagai Draft.');
        }

        // jika tombol checkout yang diklik
        if ($action === 'checkout') {
            $sale->update([
                'status' => 'COMPLETED',
                'metode_pembayaran' => $request->payment_method
            ]);

            return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil diselesaikan.');
        }

        // jika tombol cancel
        if ($action === 'cancel') {
            // Logika hapus & kembalikan stok
            foreach ($sale->itemPenjualan as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->kuantitas);
                }
            }
            $sale->itemPenjualan()->delete();
            $sale->delete();

            return redirect()->route('penjualan.create')->with('success', 'Transaksi dibatalkan.');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        // $this->authorize('delete', $penjualan);

        // 1. Tolak penghapusan HANYA jika status transaksi sudah selesai (COMPLETED / SELESAI)
        if (in_array(strtoupper($penjualan->status), ['COMPLETED', 'SELESAI'])) {
            return redirect()
                ->route('penjualan.index')
                ->with('error', 'Transaksi yang sudah selesai tidak dapat dihapus');
        }

        // 2. Cek kepemilikan user (redirect tetap di halaman riwayat jika gagal)
        if ($penjualan->user_id !== Auth::id()) {
            return redirect()
                ->route('penjualan.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus transaksi ini');
        }

        // 3. Eksekusi Hapus dan Kembalikan Stok
        DB::transaction(function () use ($penjualan) {
            foreach ($penjualan->itemPenjualan as $item) {
                // ⏫ Kembalikan stok
                if ($item->produk) {
                    $item->produk->increment('stok', $item->kuantitas);
                }
            }

            // ❌ Hapus detail item
            $penjualan->itemPenjualan()->delete();

            // ❌ Hapus data penjualan utama
            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}
