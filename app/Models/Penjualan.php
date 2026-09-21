<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'user_id',
        'total_pembayaran',
        'metode_pembayaran',
        'status'
    ];

    public static function calculateTotalPembayaran($subtotal)
    {
        $subtotal = (float) $subtotal;

        if ($subtotal > 1_000_000) {
            return (int) round($subtotal * 0.9);
        }

        return (int) round($subtotal);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ItemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'penjualan_id');
    }

    public function getTotalSetelahDiskonAttribute()
    {
        $subtotal = (float) $this->itemPenjualan()->sum('subtotal');

        return self::calculateTotalPembayaran($subtotal);
    }

    public function getTotalHppAttribute(): int
    {
        return (int) $this->itemPenjualan
            ->sum(fn ($item) => ($item->kuantitas ?? 0) * ($item->produk?->harga_beli ?? 0));
    }

    public function getKeuntunganAttribute(): int
    {
        return (int) (($this->total_pembayaran ?? 0) - $this->total_hpp);
    }
}
