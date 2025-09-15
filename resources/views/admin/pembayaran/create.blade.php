@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Bayar Pesanan #{{ $transaksi->id_transaksi }}</h2>

                    <!-- Tabel Rincian Layanan -->
                    <h5 class="mb-3">Rincian Layanan</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Layanan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->detailTransaksi as $detail)
                                    <tr>
                                        <td>{{ $detail->layanan->nama_layanan }}</td>
                                        <td class="text-center">{{ $detail->dimensi }}</td>
                                        <td class="text-end">Rp {{ number_format($detail->layanan->harga, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($detail->layanan->harga * $detail->dimensi, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Total -->
                    <p class="text-center fs-4 fw-semibold mb-4">
                        Total: <span class="text-primary">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
                    </p>

                    <!-- Tombol Bayar -->
                    <button id="pay-button" class="btn btn-primary btn-lg w-100">Bayar Sekarang</button>

                    <!-- Form Pembatalan -->
                    <form id="cancel-form" action="{{ route('admin.pesanan.batal', $transaksi->id_transaksi) }}" method="POST" class="mt-3" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <form id="cancel-button-form" class="mt-3">
                        <button type="submit" class="btn btn-danger w-100">Batal Pesanan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Midtrans Snap -->
<script src="https://app.midtrans.com/snap/snap.js" 
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

            fetch("{{ route('admin.pesanan.batal', $transaksi->id_transaksi) }}", {
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
                        window.location.href = "{{ route('admin.pesanan.index') }}";
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

                fetch("{{ route('admin.pembayaran.store', $transaksi->id_transaksi) }}", {
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
                            window.location.href = "{{ route('admin.pesanan.index') }}";
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
                        window.location.href = "{{ route('admin.pesanan.index') }}";
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
