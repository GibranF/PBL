@extends('layouts.cust')

@section('styles')
    <link href="{{ asset('assets/css/keranjang.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .text-center.mt-4.d-print-none {
            margin-bottom: 60px;
            /* kasih jarak bawah */
        }

        @media print {
            .text-center.mt-4.d-print-none {
                display: none !important;
                /* biar ga ikut ke cetak */
            }
        }
    </style>

@endsection
@section('content')
    <div class="d-flex justify-content-center align-items-center py-5">
        <div class="receipt shadow-lg p-4 rounded-3 bg-white" id="print-area"
            style="max-width: 380px; width: 100%; font-family: 'Courier New', monospace; border: 1px dashed #999;">

            <!-- Header -->
            <div class="text-center mb-3">
                <h4 class="fw-bold mb-1">ISLAND</h4>
                <p class="mb-0" style="font-size: 13px;">
                    Jl. Sultan Agung, Krajan Wetan, Temuguruh, Kec. Sempu<br>
                    Kabupaten Banyuwangi, Jawa Timur 68468
                </p>
                <p class="mb-0" style="font-size: 13px;">Telp: 0852-3563-7429</p>
                <hr style="border-top: 1px dashed #000;">
                <h5 class="mt-2 mb-0 fw-semibold">STRUK PESANAN</h5>
            </div>

            <!-- Info Transaksi -->
            <div class="mb-3" style="font-size: 13px;">
                <p class="mb-1">No. Transaksi : <strong>{{ $transaksi->id_transaksi }}</strong></p>
                <p class="mb-1">Tanggal : {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}</p>
                <p class="mb-1">Kasir : {{ $transaksi->nama_kasir ?? '-' }}</p>
                @if($transaksi->nama_pelanggan)
                    <p class="mb-0">Pelanggan : {{ $transaksi->nama_pelanggan }}</p>
                @endif
            </div>

            <hr style="border-top: 1px dashed #000;">

            <!-- Detail Layanan -->
            <table class="w-100 mb-2" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th style="text-align:left;">Layanan</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->detailTransaksi as $detail)
                        <tr>
                            <td style="text-align:left;">{{ $detail->layanan->nama_layanan }}</td>
                            <td style="text-align:center;">{{ $detail->dimensi }} {{ $detail->satuan }}</td>
                            <td style="text-align:right;">Rp {{ number_format($detail->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr style="border-top: 1px dashed #000;">

            <!-- Total -->
            <table class="w-100" style="font-size: 13px;">
                <tr>
                    <td style="text-align:left;">Total Layanan</td>
                    <td style="text-align:right;">Rp {{ number_format($transaksi->detailTransaksi->sum('subtotal')) }}</td>
                </tr>
                @if($transaksi->biaya_antar > 0)
                    <tr>
                        <td style="text-align:left;">Biaya Antar</td>
                        <td style="text-align:right;">Rp {{ number_format($transaksi->biaya_antar) }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="text-align:left;"><strong>Total Bayar</strong></td>
                    <td style="text-align:right;"><strong>Rp {{ number_format($transaksi->total) }}</strong></td>
                </tr>
            </table>

            <hr style="border-top: 1px dashed #000;">

            <!-- Pembayaran -->
            <div style="font-size: 13px;">
                <p class="mb-1">Metode Pembayaran : <strong>{{ ucfirst($transaksi->metode_pembayaran) }}</strong></p>
                <p class="mb-0">Status Pembayaran : <strong>{{ ucfirst($transaksi->status_pembayaran) }}</strong></p>
            </div>

            <!-- Footer -->
            <div class="text-center mt-3" style="font-size: 13px;">
                <p class="mb-0">*** Terima Kasih Telah Menggunakan Layanan Kami ***</p>
                <p class="mb-0">~ Semoga Hari Anda Menyenangkan ~</p>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 d-print-none">
        <button onclick="window.print()" class="btn px-5 py-2 me-2 text-white"
            style="background: linear-gradient(90deg, #8b3dff, #e056fd); border: none; border-radius: 8px;">
            <i class="fas fa-print me-1"></i> Cetak
        </button>

        <a href="{{ route('customer.pesanan.index') }}" class="btn px-5 py-2 text-white"
            style="background: linear-gradient(90deg, #e056fd, #8b3dff); border: none; border-radius: 8px;">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            /* Hanya area struk yang ditampilkan */
            #print-area {
                display: block !important;
                position: absolute;
                left: 50%;
                top: 35%;
                transform: translate(-50%, -50%);
                box-shadow: none !important;
                border: 1px dashed #000 !important;
                width: 80mm !important;
                padding: 10px;
                background: white;
            }

            /* Sembunyikan semua elemen selain #print-area */
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            /* Sembunyikan tombol dan layout admin */
            .navbar,
            .sidebar,
            .footer,
            .d-print-none {
                display: none !important;
            }

            /* Atur ukuran kertas (seperti nota kasir 80mm) */
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
@endsection