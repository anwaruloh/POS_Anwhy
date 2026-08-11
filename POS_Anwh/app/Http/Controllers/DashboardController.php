<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\LaporanPenjualanService;
use App\Services\MonitoringStokService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __construct(
        protected LaporanPenjualanService $laporanService,
        protected MonitoringStokService $stockService
    ) {}

    public function index()
    {
        // 1. Ambil Data Stok Menipis & Stok Habis (Berupa Collection)
        $stokMenipis = Produk::where('stok', '>', 0)->where('stok', '<=', 5)->get();
        $stokHabis = Produk::where('stok', '<=', 0)->get();

        // Count untuk Ringkasan
        $stokMenipisCount = $stokMenipis->count();
        $stokHabisCount = $stokHabis->count();

        // 2. Ringkasan Transaksi & Total Pendapatan
        $totalTransaksi = Penjualan::where('status', 'COMPLETED')->count();
        $totalPendapatan = Penjualan::where('status', 'COMPLETED')->sum('total_pembayaran');

        // 3. Top 5 Produk Terlaris
        $produkTerlaris = ItemPenjualan::join('produk', 'item_penjualan.produk_id', '=', 'produk.id')
            ->select(
                'produk.nama',
                DB::raw('SUM(item_penjualan.kuantitas) as total_terjual'),
                DB::raw('SUM(item_penjualan.subtotal) as total_pendapatan')
            )
            ->whereHas('penjualan', function ($query) {
                $query->where('status', 'COMPLETED');
            })
            ->groupBy('produk.id', 'produk.nama')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stokMenipis',
            'stokHabis',
            'stokMenipisCount',
            'stokHabisCount',
            'totalTransaksi',
            'totalPendapatan',
            'produkTerlaris'
        ));
    }

    public function about()
    {
        return view('about.index');
    }
}
