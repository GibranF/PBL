@extends('layouts.admin')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        h1,
        h5 {
            color: #6a0dad;
            font-weight: 600;
        }

        .card {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #f5f5fa;
            font-weight: 600;
            color: #6a0dad;
            border-bottom: 2px solid #e0d7ff;
        }

        .table th {
            background-color: #f2e9ff;
            color: #6a0dad;
        }

        .btn-primary,
        .btn-warning {
            background-color: #a020f0;
            border-color: #a020f0;
            border-radius: 50px;
        }

        .btn-primary:hover,
        .btn-warning:hover {
            background-color: #7a0eb6;
            border-color: #7a0eb6;
        }

        .btn-secondary {
            background-color: #ff69b4;
            border-color: #ff69b4;
            border-radius: 50px;
        }

        .btn-secondary:hover {
            background-color: #ff1493;
            border-color: #ff1493;
        }

        .btn i {
            margin-right: 5px;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.4rem;
            }

            .card .card-body p {
                font-size: 0.9rem;
            }

            .table th,
            .table td {
                font-size: 0.875rem;
            }

            .btn {
                font-size: 0.8rem;
                padding: 0.45rem 0.9rem;
            }

            .text-end {
                text-align: center !important;
            }
        }

        @media print {

            .btn,
            .header,
            .mt-4 {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            body,
            html {
                margin: 0;
                padding: 0;
                width: 210mm;
                height: 297mm;
                overflow: hidden;
            }

            .container {
                width: 190mm;
                margin: 10mm auto;
                padding: 0;
                font-size: 12pt;
            }

            .table-responsive,
            .table {
                page-break-inside: avoid !important;
                width: 100%;
                max-width: 190mm;
            }

            .table th,
            .table td {
                padding: 6pt;
                font-size: 11pt;
            }

            @page {
                size: A4;
                margin: 10mm;
            }

            .container {
                transform: scale(0.95);
                transform-origin: top left;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 header">
            <h1 class="mb-0"><i class="fas fa-receipt me-2 text-purple"></i> Detail Pesanan</h1>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-lg shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Card Informasi Transaksi -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-1"></i> Informasi Transaksi
            </div>
            <div class="card-body row">
                <div class="col-md-6 mb-2">
                    <p><strong>ID Transaksi:</strong> {{ $transaksi->id_transaksi }}</p>
                    <p><strong>Tanggal:</strong>
                        {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y, H:i') }}</p>
                    <p><strong>Nama Admin:</strong> {{ $transaksi->user->name }}</p>
                    <p><strong>Status Pembayaran:</strong> {{ ucfirst($transaksi->status_pembayaran ?? 'Unknown') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($transaksi->status) }}</p>
                    <p><strong>Metode Pembayaran:</strong> {{ ucfirst($transaksi->metode_pembayaran) }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p><strong>Nama Pelanggan:</strong> {{ $transaksi->nama_pelanggan }}</p>
                    <p><strong>Nomor HP:</strong> {{ $transaksi->nomor_hp }}</p>
                    <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
                    @if($transaksi->biaya_antar > 0)
                        <p><strong>Biaya Antar:</strong> Rp {{ number_format($transaksi->biaya_antar, 2) }}</p>
                        <p><strong>Jarak (km):</strong> {{ $transaksi->jarak_km }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card Detail Layanan -->
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-concierge-bell me-1"></i> Detail Layanan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>Nama Layanan</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Dimensi</th>
                                <th>Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi->detailTransaksi as $detail)
                                                <tr>
                                                    <td>{{ $detail->layanan->nama_layanan }}</td>
                                                    <td>{{ $detail->layanan->deskripsi }}</td>
                                                    <td>Rp {{ number_format($detail->layanan->harga ?? $detail->harga, ) }}</td>
                                                    <td>
                                                        {{ floor($detail->dimensi) == $detail->dimensi
                                ? intval($detail->dimensi)
                                : number_format($detail->dimensi, 2, ',', '.') }}
                                                    </td>
                                                    <td>{{ $detail->satuan }}</td>
                                                    <td>Rp {{ number_format($detail->subtotal) }}</td>
                                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Tidak ada layanan terkait transaksi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Layanan:</strong></td>
                                <td>Rp {{ number_format($transaksi->detailTransaksi->sum('subtotal')) }}</td>
                            </tr>
                            @if($transaksi->biaya_antar > 0)
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Biaya Antar:</strong></td>
                                    <td>Rp {{ number_format($transaksi->biaya_antar) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Keseluruhan:</strong></td>
                                <td>Rp {{ number_format($transaksi->total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-4 text-end">
            <button onclick="window.print()" class="btn btn-primary btn-sm me-2 shadow">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="{{ route('admin.pesanan.editStatus', $transaksi->id_transaksi) }}"
                class="btn btn-warning btn-sm shadow">
                <i class="fas fa-edit"></i> Update Status
            </a>
        </div>
    </div>
@endsection