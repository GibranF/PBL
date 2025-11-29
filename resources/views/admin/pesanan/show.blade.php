@extends('layouts.admin')

@section('content')
    @php
        $totalLayanan = $transaksi->detailTransaksi->sum('subtotal');
        $biayaAntar = $transaksi->biaya_antar ?? 0;
        $totalAkhir = $totalLayanan + $biayaAntar;
    @endphp

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

                @if ($transaksi->nama_pelanggan)
                    <p class="mb-0">Pelanggan : {{ $transaksi->nama_pelanggan }}</p>
                @endif
            </div>

            <hr style="border-top: 1px dashed #000;">

            <!-- DETAIL LAYANAN -->
            <table class="w-100 mb-2" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th style="text-align:left;">Layanan</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($transaksi->detailTransaksi as $detail)
                        <tr>
                            <td style="text-align:left;">{{ $detail->layanan->nama_layanan }}</td>
                            <td style="text-align:center;">
                                {{ rtrim(rtrim(number_format($detail->dimensi, 2, ',', ''), '0'), ',') }}
                                {{ $detail->satuan }}
                            </td>
                            <td style="text-align:right;">Rp {{ number_format($detail->subtotal) }}</td>
                        </tr>
                    @endforeach

                    <!-- TOTAL SUBTOTAL -->
                    <tr>
                        <td colspan="2" style="font-weight: bold; text-align:left;">Total Subtotal</td>
                        <td style="text-align:right; font-weight:bold;">
                            Rp {{ number_format($totalLayanan) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr style="border-top: 1px dashed #000;">

            <!-- BIAYA ANTAR -->
            <table class="w-100 mb-2" style="font-size: 13px;">
                <tbody>
                    @if ($biayaAntar > 0)
                        <tr>
                            <td style="text-align:left;">Antar Jemput</td>
                            <td style="text-align:center;">
                                {{ rtrim(rtrim(number_format($transaksi->jarak_km, 2, ',', ''), '0'), ',') }} km
                            </td>
                            <td style="text-align:right;">Rp {{ number_format($biayaAntar) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <hr style="border-top: 1px dashed #000;">

            <!-- TOTAL AKHIR DAN TOTAL DIBAYAR -->
            <table class="w-100" style="font-size: 13px;">
                <tr>
                    <td style="text-align:left;">Total Layanan</td>
                    <td style="text-align:right;">Rp {{ number_format($totalLayanan) }}</td>
                </tr>

                @if ($biayaAntar > 0)
                    <tr>
                        <td style="text-align:left;">Biaya Antar Jemput</td>
                        <td style="text-align:right;">Rp {{ number_format($biayaAntar) }}</td>
                    </tr>
                @endif

                <tr>
                    <td style="text-align:left; font-weight:bold;">Total Akhir</td>
                    <td style="text-align:right; font-weight:bold;">
                        Rp {{ number_format($totalAkhir) }}
                    </td>
                </tr>
            </table>

            <hr style="border-top: 1px dashed #000;">

            <!-- TOTAL AKHIR DAN TOTAL DIBAYAR -->
            <table class="w-100" style="font-size: 13px;">
                <tr>
                    <td style="text-align:left;">Total Dibayar</td>
                    <td style="text-align:right;">Rp {{ number_format($transaksi->total) }}</td>
                </tr>
            </table>

            <hr style="border-top: 1px dashed #000;">
            
            <!-- PEMBAYARAN -->
            <div style="font-size: 13px;">
                <p class="mb-1">Metode Pembayaran :
                    <strong>{{ ucfirst($transaksi->metode_pembayaran ?? 'Belum dipilih') }}</strong>
                </p>
                <p class="mb-0">Status Pembayaran :
                    <strong>{{ ucfirst($transaksi->status_pembayaran) }}</strong>
                </p>
            </div>

            <div class="text-center mt-3" style="font-size: 13px;">
                <p class="mb-0">*** Terima Kasih Telah Menggunakan Layanan Kami ***</p>
                <p class="mb-0">~ Semoga Hari Anda Menyenangkan ~</p>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 d-print-none">
        <button onclick="window.print()" class="btn btn-primary me-2">
            <i class="fas fa-print"></i> Cetak
        </button>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }

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

            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            .navbar,
            .sidebar,
            .footer,
            .d-print-none {
                display: none !important;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
@endsection
