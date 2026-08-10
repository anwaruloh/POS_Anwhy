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
        // 1. Cari transaksi 'OPEN' aktif milik kasir yang sedang login (tanpa membuat baru jika belum ada)
        $sale = Penjualan::where('user_id', Auth::id())
            ->where('status', 'OPEN')
            ->latest()
            ->first();

        // 2. Pencarian produk yang lebih ringkas
        $keyword = $request->input('search');

        $products = Produk::when($keyword, function ($query) use ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        })
            ->orderBy('nama')
            ->get();

        $mode = 'create';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
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
        $products = Produk::orderBy('nama')->get();
        $mode = 'edit';

        return view('penjualan.pos', compact('sale', 'products', 'mode'));
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
        $this->authorize('delete', $penjualan);

        // ❗ Pastikan hanya transaksi OPEN
        if ($penjualan->status !== 'OPEN') {
            return redirect()->route('penjualan.create')->with('errors', 'Transaksi sudah selesai tidak bisa dibatalkan');
        }

        // ❗ Pastikan milik user login (kasir)
        if ($penjualan->user_id !== Auth::id()) {
            return redirect()->route('penjualan.create');
        }

        DB::transaction(function () use ($penjualan) {

            foreach ($penjualan->itemPenjualan as $item) {
                // ⏫ kembalikan stok
                $item->produk->increment('stok', $item->kuantitas);
            }

            // ❌ hapus item
            $penjualan->itemPenjualan()->delete();

            // ❌ hapus penjualan
            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }
}
