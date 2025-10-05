@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="card component-page">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h2 class="mb-1">Daftar Pesanan</h2>
                        <p class="text-muted mb-0">Kelola semua pesanan dari pelanggan</p>
                    </div>
                    <a href="{{ route('admin.pesanan.buatpesanan') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Pesanan
                    </a>
                </div>

                {{-- 🔍 Filter dan Pencarian --}}
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
                            <button type="submit" class="btn btn-outline-secondary">Cari</button>
                        </form>
                    </div>
                </div>

                {{-- ✅ Alert Success --}}
                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '{{ session('success') }}',
                            confirmButtonText: 'OK'
                        });
                    </script>
                @endif

                {{-- 📋 Tabel Pesanan --}}
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" style="min-width: 1200px">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">ID Transaksi</th>
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
                                    <td>{{ $trans->tanggal->format('Y-m-d H:i') }}</td>
                                    <td>{{ $trans->nama_kasir ?? ($trans->user && $trans->user->usertype === 'admin' ? $trans->user->name : '') }}</td>
                                    <td>{{ $trans->nama_pelanggan }}</td>
                                    <td>
                                        @php $subtotal = $trans->detailTransaksi->sum('subtotal'); @endphp
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
                                            {{-- Awalnya hanya tombol pilih --}}
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-show-form"
                                                data-id="{{ $trans->id_transaksi }}">
                                                <i class="fas fa-edit"></i> Pilih
                                            </button>

                                            {{-- Form tersembunyi, muncul saat tombol pilih ditekan --}}
                                            <form class="form-set-metode d-none mt-2"
                                                id="form-metode-{{ $trans->id_transaksi }}"
                                                data-url="{{ route('admin.pembayaran.setMetodeDanBayar', $trans->id_transaksi) }}">
                                                @csrf
                                                <div class="d-flex align-items-center gap-2">
                                                    <select name="metode_pembayaran"
                                                        class="form-select form-select-sm w-auto" required>
                                                        <option value="" disabled selected>Pilih Metode</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="online">Online</option>
                                                    </select>
                                                    <button type="submit"
                                                        class="btn btn-m btn-outline-success d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2">
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

                                            {{-- ✅ Aksi Bayar / Batal --}}
                                            @if ($trans->status !== 'pesanan selesai')
                                                {{-- 🔹 Tampilkan Bayar hanya kalau belum dibayar --}}
                                                @if ($trans->status_pembayaran == 'belum dibayar')
                                                    @if (empty($trans->metode_pembayaran))
                                                    @elseif ($trans->metode_pembayaran == 'cash')
                                                        {{-- 💵 Bayar Cash --}}
                                                        <button class="btn btn-sm btn-outline-success px-3 btn-bayar-cash"
                                                            data-url="{{ route('admin.pembayaran.bayarCash', $trans->id_transaksi) }}">
                                                            <i class="fas fa-credit-card"></i>
                                                        </button>
                                                    @else
                                                        {{-- 🌐 Online Payment --}}
                                                        <a href="{{ route('admin.pembayaran.create', $trans->id_transaksi) }}"
                                                            class="btn btn-sm btn-outline-success px-3">
                                                            <i class="fas fa-credit-card"></i>
                                                        </a>
                                                    @endif
                                                @endif

                                                {{-- ❌ Tombol batal selalu ada kalau belum selesai --}}
                                                <button class="btn btn-sm btn-outline-danger px-3 btn-batal"
                                                    data-url="{{ route('admin.pesanan.batal', $trans->id_transaksi) }}">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Tidak ada data pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 📌 Pagination --}}
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

    {{-- ✅ JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 🔹 Tampilkan form metode saat klik "Pilih"
        document.querySelectorAll('.btn-show-form').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = btn.dataset.id;
                let form = document.getElementById('form-metode-' + id);

                btn.classList.add('d-none'); // sembunyikan tombol pilih
                form.classList.remove('d-none'); // tampilkan form
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // 🔹 Set Metode Pembayaran
            document.querySelectorAll('.form-set-metode').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const actionUrl = form.dataset.url;
                    const formData = new FormData(form);

                    Swal.fire({
                        title: 'Konfirmasi Metode?',
                        text: 'Pastikan pilihan metode benar.',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Pilih',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(actionUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Berhasil', data.message ||
                                                'Metode disimpan', 'success')
                                            .then(() => window.location.reload());
                                    } else {
                                        Swal.fire('Gagal', data.message ||
                                            'Gagal menyimpan metode.', 'error');
                                    }
                                })
                                .catch(() => Swal.fire('Error', 'Terjadi kesalahan.',
                                    'error'));
                        }
                    });
                });
            });

            // 🔹 Bayar Cash
            document.querySelectorAll('.btn-bayar-cash').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const actionUrl = btn.dataset.url;

                    Swal.fire({
                        title: 'Bayar Sekarang?',
                        text: 'Pembayaran akan langsung disimpan.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Bayar',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(actionUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Berhasil', data.message, 'success')
                                            .then(() => window.location.reload());
                                    } else {
                                        Swal.fire('Gagal', data.message ||
                                            'Pembayaran gagal.', 'error');
                                    }
                                })
                                .catch(() => Swal.fire('Error',
                                    'Terjadi kesalahan saat bayar.', 'error'));
                        }
                    });
                });
            });
            // 🔹 Batalkan Pesanan
            document.querySelectorAll('.btn-batal').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const actionUrl = btn.dataset.url;

                    Swal.fire({
                        title: 'Batalkan Pesanan?',
                        text: 'Pesanan akan dihapus dari sistem!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('_token', '{{ csrf_token() }}');
                            formData.append('_method', 'DELETE');

                            fetch(actionUrl, {
                                    method: "POST", // spoofing DELETE
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: data.message ||
                                                'Pesanan berhasil dihapus',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            // 🔹 Redirect setelah user klik "OK"
                                            window.location.href =
                                                "{{ route('admin.pesanan.index') }}";
                                        });
                                    } else {
                                        Swal.fire('Gagal', data.message ||
                                            'Gagal membatalkan pesanan.', 'error');
                                    }
                                })
                                .catch(() => Swal.fire('Error', 'Terjadi kesalahan.',
                                    'error'));
                        }
                    });
                });
            });

        });
    </script>
@endsection
