@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="card component-page">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h2 class="mb-1">Layanan Diarsipkan</h2>
                        <p class="text-muted mb-0">Kelola layanan yang sudah diarsipkan</p>
                    </div>
                    <a href="{{ route('admin.layanan.index') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Layanan
                    </a>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Cari layanan diarsipkan...">
                            <button class="btn btn-outline-secondary" type="button">Cari</button>
                        </div>
                    </div>
                    {{-- <div class="col-md-6 mt-2 mt-md-0">
                        <div class="d-flex justify-content-md-end">
                            <select class="form-select me-2" style="max-width: 200px;">
                                <option selected>Filter Kategori</option>
                                <option>Semua</option>
                                <option>Populer</option>
                                <option>Terbaru</option>
                            </select>
                            <button class="btn btn-outline-secondary">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </div> --}}
                </div>

                <!-- Tabel Layanan Diarsip -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" style="min-width: 800px">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 30%">Nama Layanan</th>
                                <th style="width: 20%">Harga</th>
                                <th style="width: 30%">Deskripsi</th>
                                <th style="width: 5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($layananArsip as $layanan)
                                <tr>
                                    <td class="fw-bold">{{ $layanan->id_layanan }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="bg-light rounded p-2" style="width: 40px; height: 40px;">
                                                    <i
                                                        class="fas fa-concierge-bell text-secondary d-flex justify-content-center align-items-center h-100"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $layanan->nama_layanan }}</h6>
                                                <small class="text-muted">Kategori: Umum</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fs-6">Rp
                                            {{ number_format($layanan->harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <p class="mb-0 text-truncate" style="max-width: 300px;">{{ $layanan->deskripsi }}</p>
                                    </td>
                                    <td class="">
                                        <div class="btn-group" role="group">
                                            <!-- Tombol Restore -->
                                            <form action="{{ route('admin.layanan.restore', $layanan->id_layanan) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin mengembalikan layanan ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success px-3"
                                                    title="Kembalikan Layanan">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada layanan yang diarsipkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <p class="mb-0 text-muted">
                        Menampilkan {{ $layananArsip->firstItem() ?? 0 }} sampai {{ $layananArsip->lastItem() ?? 0 }} dari
                        {{ $layananArsip->total() ?? 0 }} entri
                    </p>

                    <div class="col-md-6">
                        <nav aria-label="Page navigation" class="float-md-end">
                            {{ $layananArsip->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection