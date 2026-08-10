<?php

namespace App\Http\Controllers;

use App\Http\Requests\Produk\StoreRequest;
use App\Http\Requests\Produk\UpdateRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $this->authorize('viewAny', Produk::class);

        $keyword = $request->input('search');

        if ($keyword) {
            $produks = Produk::when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', '%' . $keyword . '%');
            })
                ->orderBy('nama')
                ->paginate(10)
                ->withQueryString();
        } else {
            // Mengambil data produk terbaru
            $produks = Produk::latest()->paginate(10)->withQueryString();
        }

        // Variabel disamakan menjadi 'produks'
        return view('produk.index', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Produk::class);

        return view('produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Produk::class);

        $dataReq = $request->validated();

        $data['user_id']    = Auth::id();
        $data['nama']       = $dataReq['nama'] ?? $dataReq['name'] ?? null;
        $data['harga_beli'] = $dataReq['purchase_price'] ?? $dataReq['harga_beli'] ?? 0;
        $data['harga_jual'] = $dataReq['selling_price'] ?? $dataReq['harga_jual'] ?? 0;
        $data['stok']       = $dataReq['stock'] ?? $dataReq['stok'] ?? 0;

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

        return view('produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Produk $produk)
    {
        $this->authorize('update', $produk);

        $dataReq = $request->validated();

        // DIPERBAIKI: Menggunakan kunci 'nama' agar sesuai dengan kolom database/model
        $data = [
            'user_id'    => Auth::id(),
            'nama'       => $dataReq['nama'] ?? $dataReq['name'] ?? $produk->nama,
            'harga_beli' => $dataReq['purchase_price'] ?? $dataReq['harga_beli'] ?? $produk->harga_beli,
            'harga_jual' => $dataReq['selling_price'] ?? $dataReq['harga_jual'] ?? $produk->harga_jual,
            'stok'       => $dataReq['stock'] ?? $dataReq['stok'] ?? $produk->stok,
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
            return redirect()->route('produk.index')
                ->with('error', 'Gagal menghapus! Produk ini sudah terikat dengan riwayat transaksi penjualan.');
        }
    }
}
