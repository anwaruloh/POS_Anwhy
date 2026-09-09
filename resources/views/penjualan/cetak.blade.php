<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi #{{ $penjualan->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm; /* Ukuran kertas kasir umum */
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; }
        @media print {
            @page { margin: 0; }
            body { margin: 10px; }
        }
    </style>
</head>
<body>
    <div class="text-center">
        <h3 style="margin: 0;">POS KASIR</h3>
        <p style="margin: 2px 0;">Jl. Raya No. 123</p>
        <p style="margin: 2px 0;">{{ $penjualan->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="line"></div>

    <table>
        @foreach($penjualan->itemPenjualan as $item)
        <tr>
            <td colspan="2"><strong>{{ $item->produk->nama ?? 'Produk' }}</strong></td>
        </tr>
        <tr>
            <td>{{ $item->jumlah }} x Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td><strong>Total:</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>Bayar:</td>
            <td class="text-right">Rp {{ number_format($penjualan->bayar ?? $penjualan->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali:</td>
            <td class="text-right">Rp {{ number_format(($penjualan->bayar ?? $penjualan->total_harga) - $penjualan->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>
    <p class="text-center">-- Terima Kasih --</p>

    <script>
        // Otomatis munculkan pop-up cetak/print saat halaman dibuka
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>