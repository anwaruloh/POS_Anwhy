<?php

namespace App\Http\Controllers;

use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Kategori; // 1. Ditambahkan: Import model Kategori
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Produk::class);

        // Ambil input pencarian & filter kategori
        $keyword = $request->input('search');
        $kategoriId = $request->input('kategori_id');

        // Query dasar produk beserta relasi kategorinya
        $query = Produk::with('kategori');

        // 2. Filter berdasarkan Kategori jika dipilih
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }

        // 3. Filter berdasarkan Kata Kunci / Nama Produk jika diisi
        if ($keyword) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        }

        // Ambil data produk
        $produks = $query->latest()->paginate(10)->withQueryString();

        // Ambil semua data kategori untuk opsi dropdown di view
        $kategoris = Kategori::all();

        return view('produk.index', compact('produks', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Produk::class);

        // Ambil kategori agar bisa dipilih di form tambah produk
        $kategoris = Kategori::all();

        return view('produk.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Produk::class);

        $dataReq = $request->validated();

        $data['user_id']     = Auth::id();
        $data['kategori_id'] = $dataReq['kategori_id'] ?? null; // Simpan kategori_id
        $data['nama']        = $dataReq['nama'] ?? $dataReq['name'] ?? null;
        $data['harga_beli']  = $dataReq['purchase_price'] ?? $dataReq['harga_beli'] ?? 0;
        $data['harga_jual']  = $dataReq['selling_price'] ?? $dataReq['harga_jual'] ?? 0;
        $data['stok']        = $dataReq['stock'] ?? $dataReq['stok'] ?? 0;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
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
    public function edit(Produk $produk)
    {
        $this->authorize('update', $produk);

        // Ambil kategori agar bisa dipilih di form edit produk
        $kategoris = Kategori::all();

        return view('produk.edit', compact('produk', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Produk $produk)
    {
        $this->authorize('update', $produk);

        $dataReq = $request->validated();

        $data = [
            'user_id'     => Auth::id(),
            'kategori_id' => $request->kategori_id ?? $produk->kategori_id, // Update kategori_id
            'nama'        => $dataReq['nama'] ?? $dataReq['name'] ?? $produk->nama,
            'harga_beli'  => $dataReq['purchase_price'] ?? $dataReq['harga_beli'] ?? $produk->harga_beli,
            'harga_jual'  => $dataReq['selling_price'] ?? $dataReq['harga_jual'] ?? $produk->harga_jual,
            'stok'        => $dataReq['stock'] ?? $dataReq['stok'] ?? $produk->stok,
        ];

        // Jika upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            // Simpan foto baru
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $this->authorize('delete', $produk);

        try {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $produk->delete();

            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.produk.index')
                ->with('error', 'Gagal menghapus! Produk ini sudah terikat dengan riwayat transaksi penjualan.');
        }
    }
}
