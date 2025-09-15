@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="card component-page">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h2 class="mb-1">Daftar Pesanan</h2>
                        <p class="text-muted mb-0">Kelola semua pesanan dari pelanggan</p>
                    </div>
                    <a href="{{ route('admin.pesanan.buatpesanan') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Pesanan
                    </a>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.pesanan.index') }}">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Cari pesanan..."
                                    value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 mt-2 mt-md-0">
                        <form method="GET" action="{{ route('admin.pesanan.index') }}" class="d-flex">
                            <select name="status" class="form-select me-2" style="max-width: 200px;">
                                <option value="">Semua</option>
                                <option value="belum diproses"
                                    {{ request('status') == 'belum diproses' ? 'selected' : '' }}>Belum Diproses</option>
                                <option value="pesanan diproses"
                                    {{ request('status') == 'pesanan diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="pesanan selesai"
                                    {{ request('status') == 'pesanan selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            <button type="submit" class="btn btn-outline-secondary">
                                <i></i> Cari
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tabel Pesanan -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" style="min-width: 1200px">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Kode TRX</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Admin</th>
                                <th class="text-center">Pelanggan</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Pembayaran</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Metode</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi as $trans)
                                <tr>
                                    <td class="fw-bold">{{ $trans->id_transaksi }}</td>
                                    <td>{{($trans->tanggal)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        {{ $trans->user->usertype === 'admin' ? $trans->user->name : '' }}
                                    </td>

                                    <td>{{ $trans->nama_pelanggan }}</td>
                                    <td>
                                        @php
                                            $subtotal = $trans->detailTransaksi->sum('subtotal');
                                        @endphp
                                        <span class="badge bg-light text-dark fs-6">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ empty($trans->status_pembayaran) || $trans->status_pembayaran == 'belum dibayar' ? 'bg-danger' : 'bg-success' }}">
                                            {{ ucfirst($trans->status_pembayaran ?? 'Belum Dibayar') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge d-block text-center w-100
                                            {{ $trans->status == 'belum diproses' ? 'bg-danger' : ($trans->status == 'pesanan diproses' ? 'bg-warning' : 'bg-success') }}">
                                            {{ ucfirst($trans->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($trans->status_pembayaran == 'belum dibayar' && empty($trans->metode_pembayaran))
                                            <form
                                                action="{{ route('admin.pembayaran.setMetodeDanBayar', $trans->id_transaksi) }}"
                                                method="POST"
                                                onsubmit="return confirm('Konfirmasi metode pembayaran ini?')">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <select name="metode_pembayaran" class="form-select form-select-sm"
                                                        required>
                                                        <option value="" disabled selected>Pilih Metode</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="online">Online</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($trans->metode_pembayaran ?? '-') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.pesanan.show', $trans->id_transaksi) }}"
                                                class="btn btn-sm btn-outline-primary px-3 me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.pesanan.editStatus', $trans->id_transaksi) }}"
                                                class="btn btn-sm btn-outline-warning px-3 me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @if ($trans->status_pembayaran == 'belum dibayar')
                                            @if (empty($trans->metode_pembayaran))
                                                <!-- Pilihan metode pembayaran -->

                                                <form action="{{ route('admin.pesanan.batal', $trans->id_transaksi) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                                    style="display:inline-block; margin-left:5px;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            @elseif ($trans->metode_pembayaran == 'cash')
                                                <!-- Jika metode cash, tombol bayar langsung trigger update via form POST -->
                                                <form
                                                    action="{{ route('admin.pembayaran.bayarCash', $trans->id_transaksi) }}"
                                                    method="POST" style="display:inline-block;"
                                                    onsubmit="return confirm('Bayar dengan cash sekarang?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success px-3">
                                                        <i class="fas fa-credit-card"></i> Bayar
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.pesanan.batal', $trans->id_transaksi) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                                    style="display:inline-block; margin-left:5px;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Kalau bukan cash (misal online), arahkan ke halaman create pembayaran -->
                                                <a href="{{ route('admin.pembayaran.create', $trans->id_transaksi) }}"
                                                    class="btn btn-sm btn-outline-success px-3">
                                                    <i class="fas fa-credit-card"></i>Bayar
                                                </a>
                                                <form action="{{ route('admin.pesanan.batal', $trans->id_transaksi) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                                    style="display:inline-block; margin-left:5px;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Tidak ada data pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            Menampilkan {{ $transaksi->firstItem() ?? 0 }} sampai {{ $transaksi->lastItem() ?? 0 }} dari
                            total {{ $transaksi->total() }} entri
                        </p>
                    </div>
                    <div class="col-md-6">
                        <nav class="float-md-end" aria-label="Pagination">
                            {{ $transaksi->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
