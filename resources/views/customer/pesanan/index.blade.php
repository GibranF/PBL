@extends('layouts.cust')

@section('title', 'Daftar Pesanan')

@section('styles')
    <link href="{{ asset('assets/css/keranjang.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
    <section id="keranjang" class="section">
        <div class="container">
            <!-- Header Section -->
            <div class="header-section mt-4">
                <div class="header-content">
                    <h1 class="section-title">Keranjang Pesanan Kamu</h1>
                    <div class="header-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid mb-5">
                <div class="stat-card">
                    <div class="stat-icon danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">
                            {{ $transaksi->whereIn('status_pembayaran', [null, 'belum dibayar'])->count() }}</h3>
                        <p class="stat-label">Belum Dibayar</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $transaksi->where('status_pembayaran', 'sudah dibayar')->count() }}</h3>
                        <p class="stat-label">Sudah Dibayar</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $transaksi->count() }}</h3>
                        <p class="stat-label">Total Pesanan</p>
                    </div>
                </div>
            </div>

            <!-- Unpaid Transactions -->
            <div class="transaction-group mb-5">
                <div class="group-header danger">
                    <div class="group-title-wrapper">
                        <i class="group-icon fas fa-exclamation-triangle"></i>
                        <h2 class="group-title">Pesanan Belum Dibayar</h2>
                    </div>
                    <div class="group-badge">
                        {{ $transaksi->whereIn('status_pembayaran', [null, 'belum dibayar'])->count() }} item
                    </div>
                </div>

                <div class="group-content">
                    <div id="unpaid-transactions">
                        @if($transaksi->whereIn('status_pembayaran', [null, 'belum dibayar'])->count() > 0)
                            <div class="transaction-cards">
                                @foreach($transaksi->whereIn('status_pembayaran', [null, 'belum dibayar']) as $trx)
                                    <div class="transaction-card" data-transaksi-id="{{ $trx->id_transaksi }}">
                                        <div
                                            class="card-status-indicator {{ $trx->status == 'belum diproses' ? 'danger' : ($trx->status == 'pesanan diproses' ? 'warning' : 'success') }}">
                                        </div>

                                        <div class="card-header">
                                            <div class="card-id-wrapper">
                                                <span class="card-id">{{ $trx->id_transaksi }}</span>
                                                <span class="card-date">{{ $trx->tanggal->format('d M Y') }}</span>
                                            </div>
                                            <span
                                                class="card-status badge {{ $trx->status == 'belum diproses' ? 'bg-danger' : ($trx->status == 'pesanan diproses' ? 'bg-warning' : 'bg-success') }}">
                                                <i
                                                    class="fas {{ $trx->status == 'belum diproses' ? 'fa-clock' : ($trx->status == 'pesanan diproses' ? 'fa-cog fa-spin' : 'fa-check') }}"></i>
                                                {{ ucfirst($trx->status) }}
                                            </span>
                                        </div>

                                        <div class="card-body">
                                            <div class="info-grid">
                                                <div class="info-item">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <div class="info-content">
                                                        <span class="info-label">Waktu Order</span>
                                                        <span class="info-value">{{ $trx->tanggal->format('H:i') }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                    <div class="info-content">
                                                        <span class="info-label">Total Bayar</span>
                                                        <span class="info-value price">Rp
                                                            {{ number_format($trx->total, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-actions">
                                            @if ($trx->status_pembayaran == 'belum dibayar' || is_null($trx->status_pembayaran))
                                                <!-- Button Bayar -->
                                                <a href="#" class="btn btn-pay pay-now" data-transaksi-id="{{ $trx->id_transaksi }}">
                                                    <i class="fas fa-credit-card"></i>
                                                    <span>Bayar Sekarang</span>
                                                </a>

                                                <!-- Cancel Button -->
                                                <button type="button" class="btn btn-cancel cancel-btn"
                                                    data-transaksi-id="{{ $trx->id_transaksi }}">
                                                    <i class="fas fa-times"></i>
                                                    <span>Batalkan</span>
                                                </button>

                                                <!-- Cancel Form (Hidden) -->
                                                <form id="cancel-form-{{ $trx->id_transaksi }}"
                                                    action="{{ route('customer.pesanan.batal', $trx->id_transaksi) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state" id="unpaid-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-bell-slash"></i>
                                </div>
                                <h3 class="empty-title">Tidak Ada Pesanan Tertunda</h3>
                                <p class="empty-text">Silahkan buat pesanan baru dan atasi cucian numpuk</p>
                                <a href="{{ route('customer.pesanan.create') }}">Laundry sekarang</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Paid Transactions -->
            <div class="transaction-group">
                <div class="group-header success">
                    <div class="group-title-wrapper">
                        <i class="group-icon fas fa-check-circle"></i>
                        <h2 class="group-title">Pesanan Sudah Dibayar</h2>
                    </div>
                    <div class="group-badge">
                        {{ $transaksi->where('status_pembayaran', 'sudah dibayar')->count() }} item
                    </div>
                </div>

                <div class="group-content">
                    <div id="paid-transactions">
                        @if($transaksi->where('status_pembayaran', 'sudah dibayar')->count() > 0)
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Status Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Total Bayar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi->where('status_pembayaran', 'sudah dibayar') as $trx)
                                        <tr>
                                            <td>{{ $trx->id_transaksi }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $trx->status == 'selesai' ? 'bg-success' : ($trx->status == 'pesanan diproses' ? 'bg-warning' : 'bg-secondary') }}">
                                                    {{ ucfirst($trx->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $trx->tanggal->format('d M Y') }}</td>
                                            <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($trx->status === 'pesanan selesai')
                                                    <a href="{{ route('customer.pesanan.show', $trx->id_transaksi) }}"
                                                        class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-receipt"></i> Lihat Struk
                                                    </a>
                                                @else
                                                    <span class="text-muted">Menunggu Selesai</span>
                                                @endif
                                                
                                                <a href="{{ route('customer.pesanan.detail', $trx->id_transaksi) }}"
                                                class="btn btn-sm btn-outline-secondary px-3 me-1">
                                                <i class="fas fa-eye"></i>Lihat Detail
                                            </a>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state" id="paid-empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h3 class="empty-title">Belum Ada Pesanan Dibayar</h3>
                                <p class="empty-text">Selesaikan pembayaran pesanan untuk melihat riwayat transaksi di sini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        console.log('✅ JavaScript loaded!');
        
        document.addEventListener('DOMContentLoaded', function () {
            console.log('✅ DOM loaded!');
            
            const payButtons = document.querySelectorAll('.pay-now');
            const cancelButtons = document.querySelectorAll('.cancel-btn');
            
            console.log('🔍 Pay buttons:', payButtons.length);
            console.log('🔍 Cancel buttons:', cancelButtons.length);
            
            // ========== HANDLER BAYAR ==========
            payButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('🎯 Tombol Bayar diklik!');
                    
                    const transaksiId = this.getAttribute('data-transaksi-id');
                    const paymentUrl = "{{ url('customer/pembayaran/create') }}/" + transaksiId;
                    
                    Swal.fire({
                        title: 'Konfirmasi Pembayaran',
                        text: `Lanjutkan pembayaran pesanan #${transaksiId}?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, bayar!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = paymentUrl;
                        }
                    });
                });
            });

            // ========== HANDLER BATALKAN ==========
            cancelButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    console.log('🎯 Tombol Batalkan diklik!');
                    
                    const transaksiId = this.getAttribute('data-transaksi-id');
                    const form = document.getElementById(`cancel-form-${transaksiId}`);

                    if (!form) {
                        Swal.fire('Error', 'Form tidak ditemukan!', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Yakin ingin membatalkan?',
                        html: `Pesanan <strong>#${transaksiId}</strong> akan dibatalkan permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, batalkan',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            button.disabled = true;
                            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membatalkan...';

                            fetch(form.action, {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json",
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify({ _method: "DELETE" })
                            })
                            .then(res => res.ok ? res.json() : Promise.reject('HTTP Error'))
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Berhasil!', data.message, 'success')
                                        .then(() => window.location.reload());
                                } else {
                                    throw new Error(data.message);
                                }
                            })
                            .catch(err => {
                                Swal.fire('Gagal!', err.message || err, 'error');
                                button.disabled = false;
                                button.innerHTML = '<i class="fas fa-times"></i> <span>Batalkan</span>';
                            });
                        }
                    });
                });
            });
        });
    </script>
@endpush