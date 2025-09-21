@extends('layouts.cust')

@section('styles')
<link href="{{ asset('assets/css/pembayaran.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
@section('content')
<div class="payment-container">
    <div class="payment-wrapper">
        <div class="payment-card">
            <!-- Header Section -->
            <div class="payment-header">
                <div class="payment-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                </div>
                <h1 class="payment-title">Pembayaran Pesanan</h1>
                <p class="payment-subtitle">Order #{{ $transaksi->id_transaksi }}</p>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2 class="section-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2h-4"/>
                        <path d="M9 11V6a3 3 0 0 1 6 0v5"/>
                    </svg>
                    Rincian Layanan
                </h2>
                
                <div class="order-items">
                    @foreach ($transaksi->detailTransaksi as $detail)
                        <div class="order-item">
                            <div class="item-info">
                                <h3 class="item-name">{{ $detail->layanan->nama_layanan }}</h3>
                                <span class="item-quantity">{{ $detail->dimensi }} item</span>
                            </div>
                            <div class="item-pricing">
                                <span class="item-price">Rp {{ number_format($detail->layanan->harga, 0, ',', '.') }}</span>
                                <span class="item-total">Rp {{ number_format($detail->layanan->harga * $detail->dimensi, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total Section -->
                <div class="total-section">
                    <div class="total-breakdown">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="total-row final-total">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Actions -->
            <div class="payment-actions">
                <button id="pay-button" class="btn btn-pay">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                    Bayar Sekarang
                </button>
                
                <div class="security-info">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                    <span>Pembayaran aman dengan enkripsi SSL</span>
                </div>

                <!-- Cancel Form (Hidden) -->
                <form id="cancel-form" action="{{ route('customer.pesanan.batal', $transaksi->id_transaksi) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

                <!-- Cancel Button Form -->
                <form id="cancel-button-form" class="cancel-form">
                    <button type="submit" class="btn btn-cancel">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        Batalkan Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    let transaksiDihapus = false;
    let pembayaranSelesai = false;

    // Untuk menjaga riwayat halaman agar event popstate aktif
    window.history.pushState({ page: 1 }, "", "");

    // Konstanta snap token dari backend
    const snapToken = '{{ $snapToken }}';

    window.addEventListener('popstate', function(event) {
        if (transaksiDihapus || pembayaranSelesai) return;

        Swal.fire({
            title: 'Yakin ingin membatalkan pesanan?',
            text: 'Pesanan akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                transaksiDihapus = true;
                document.getElementById('cancel-form').submit();
            } else {
                window.history.pushState({ page: 1 }, "", "");
            }
        });
    });

    document.getElementById('cancel-button-form').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Yakin ingin membatalkan?',
        text: "Pesanan akan dibatalkan permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, batalkan',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            // Disable tombol supaya gak double submit
            this.querySelector('button[type="submit"]').disabled = true;

            fetch("{{ route('customer.pesanan.batal', $transaksi->id_transaksi) }}", {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                },
            })
            .then(res => {
                if (!res.ok) {
                    // Ambil teks error supaya mudah debugging
                    return res.text().then(text => {
                        throw new Error(`HTTP ${res.status} - Response: ${text}`);
                    });
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message || 'Pesanan berhasil dibatalkan.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('halaman.landing-page') }}";
                    });
                } else {
                    throw new Error(data.message || 'Gagal membatalkan pesanan.');
                }
            })
            .catch(err => {
                Swal.fire('Error', err.message, 'error');
                this.querySelector('button[type="submit"]').disabled = false;
            });
        }
    });
});


    window.addEventListener('beforeunload', function (e) {
        if (transaksiDihapus || pembayaranSelesai) return;
        e.preventDefault();
        e.returnValue = '';
    });

    document.getElementById('pay-button').addEventListener('click', function () {
        let payButton = this;
        payButton.disabled = true;

        if (!snapToken) {
            Swal.fire('Gagal', 'Token pembayaran tidak tersedia.', 'error');
            payButton.disabled = false;
            return;
        }

        window.snap.pay(snapToken, {
            onSuccess: function(result){
                pembayaranSelesai = true;

                fetch("{{ route('customer.pembayaran.store', $transaksi->id_transaksi) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        snap_token: snapToken
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.text().then(text => {
                            throw new Error(`HTTP error ${res.status}: ${text}`);
                        });
                    }
                    const contentType = res.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return res.text().then(text => {
                            throw new Error(`Expected JSON, but received: ${text}`);
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: data.message || 'Data pembayaran telah disimpan.',
                        }).then(() => {
                            window.location.href = "{{ route('halaman.landing-page') }}";
                        });
                    } else {
                        throw new Error(data.error || 'Pembayaran berhasil, tetapi data tidak disimpan.');
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Pembayaran berhasil, tetapi gagal menyimpan ke database: ' + err.message,
                    }).then(() => {
                        window.location.href = "{{ route('customer.pesanan.index') }}";
                    });
                })
                .finally(() => {
                    payButton.disabled = false;
                });
            },
            onPending: function(result){
                Swal.fire('Menunggu', 'Menunggu penyelesaian pembayaran.', 'info');
                payButton.disabled = false;
            },
            onError: function(result){
                Swal.fire('Gagal', 'Pembayaran gagal.', 'error');
                payButton.disabled = false;
            },
            onClose: function(){
                Swal.fire('Ditutup', 'Kamu menutup popup pembayaran.', 'info');
                payButton.disabled = false;
            }
        });
    });
</script>
@endsection