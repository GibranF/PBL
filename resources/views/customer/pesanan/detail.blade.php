@extends('layouts.cust')

@section('styles')
@endsection

@section('content')
 {{-- PAGE HEADER --}}
        <div class="pb-2 mb-3 border-bottom">
        </div>

        <div class="card shadow-sm border-0 rounded-0">

            {{-- CARD HEADER COLOR --}}
            <div class="card-header rounded-0 py-3" style="background:#561c6d; border-bottom:3px solid #561c6d;">
                <h6 class="mb-0 fw-semibold text-white">Informasi Transaksi</h6>
            </div>

            {{-- CARD BODY --}}
            <div class="card-body pb-1">

                {{-- SECTION TAG --}}
                <span class="badge px-3 py-2 mb-3" style="background:#e8eaff; color:#9624a0; border-radius:0;">
                    Informasi Utama
                </span>

                {{-- GRID --}}
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm mb-3">
                            <tr>
                                <th class="text-muted">ID Transaksi</th>
                                <td class="text-dark fw-semibold">{{ $transaksi->id_transaksi }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal</th>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Pelanggan</th>
                                <td>{{ $transaksi->nama_pelanggan }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-sm mb-3">
                            <tr>
                                <th class="text-muted">Kasir</th>
                                <td>{{ $transaksi->nama_kasir ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Metode Pembayaran</th>
                                <td>{{ $transaksi->metode_pembayaran ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status Pembayaran</th>
                                <td class="fw-semibold">
                                    <span class="badge rounded-0 px-2 py-1" style="background:#e2f5e9; color:#0a8f3a;">
                                        {{ ucfirst($transaksi->status_pembayaran) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- LAYANAN --}}
                <span class="badge px-3 py-2 mt-4 mb-2" style="background:#e8eaff; color:#ba74c4; border-radius:0;">
                    Daftar Layanan
                </span>

                <div class="table-responsive">
                    <table class="table table-hover table-striped rounded-0">
                        <thead style="background:#f3f4ff;">
                            <tr>
                                <th class="text-secondary">Layanan</th>
                                <th class="text-secondary">Qty</th>
                                <th class="text-secondary">Dimensi</th>
                                <th class="text-secondary">Harga</th>
                                <th class="text-secondary text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi->detailTransaksi as $d)
                                <tr>
                                    <td>{{ $d->layanan->nama_layanan }}</td>

                                    <td>
                                        {{ rtrim(rtrim(number_format($d->dimensi, 2, ',', ''), '0'), ',') }}
                                    </td>

                                    <td>{{ $d->satuan }}</td>

                                    <td>Rp {{ number_format($d->layanan->harga, 0, ',', '.') }}</td>

                                    <td class="fw-semibold text-end">
                                        Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach

                            {{-- ROW TOTAL SUBTOTAL LAYANAN (KANAN BAWAH) --}}
                            <tr style="background:#fafaff;">
                                <td colspan="4" class="text-end fw-bold text-dark">
                                    Total Subtotal
                                </td>
                                <td class="fw-bold text-dark text-end">
                                    Rp {{ number_format($transaksi->detailTransaksi->sum('subtotal'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                {{-- BIAYA ANTAR --}}
                @if ($transaksi->biaya_antar > 0)
                    <span class="badge px-3 py-2 my-3" style="background:#e8eaff; color:#ba74c4; border-radius:0;">
                        Biaya Antar Jemput
                    </span>

                    <table class="table table-sm">
                        <tr>
                            <th class="text-muted">Jarak</th>
                            <td>{{ rtrim(rtrim(number_format($transaksi->jarak_km, 2, ',', ''), '0'), ',') }} km</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Biaya</th>
                            <td class="fw-semibold">Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                @endif

                {{-- TOTAL --}}
                <span class="badge px-3 py-2 mt-4 mb-2" style="background:#e8eaff; color:#ba74c4; border-radius:0;">
                    Total Pembayaran
                </span>

                <table class="table table-sm">
                    <tr>
                        <th class="text-muted">Total Layanan</th>
                        <td>Rp {{ number_format($transaksi->detailTransaksi->sum('subtotal'), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Biaya Antar</th>
                        <td>Rp {{ number_format($transaksi->biaya_antar ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th class="text-dark">Total Akhir</th>
                        <td class="fw-bold text-dark">
                            Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

            </div>

            {{-- FOOTER --}}
            <div class="card-footer bg-light border-top py-2 text-end rounded-0">
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

        </div>
@endsection