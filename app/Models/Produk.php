<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'foto',
        'nama',
        'harga_beli',
        'harga_jual',
        'stok'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function itemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'produk_id');
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
