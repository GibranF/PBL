@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="card component-page">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h2 class="mb-1">Daftar Layanan</h2>
                        <p class="text-muted mb-0">Kelola semua layanan yang tersedia</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.layanan.archive') }}" class="btn btn-secondary shadow-sm">
                            <i class="fas fa-archive me-2"></i> Layanan Diarsipkan
                        </a>
                        <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Layanan
                        </a>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Cari layanan...">
                            <button class="btn btn-outline-secondary" type="button">Cari</button>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2 mt-md-0">
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
                    </div>
                </div>

                <!-- Tabel Layanan -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" style="min-width: 800px">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 25%">Nama Layanan</th>
                                <th style="width: 15%">Harga</th>
                                <th style="width: 35%">Deskripsi</th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($layanan as $layananAdmin)
                                <tr>
                                    <td class="fw-bold">{{ $layananAdmin->id_layanan }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="bg-light rounded p-2" style="width: 40px; height: 40px;">
                                                    <i
                                                        class="fas fa-concierge-bell text-primary d-flex justify-content-center align-items-center h-100"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $layananAdmin->nama_layanan }}</h6>
                                                <small class="text-muted">Kategori: Umum</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fs-6">Rp
                                            {{ number_format($layananAdmin->harga, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <p class="mb-0 text-truncate" style="max-width: 300px;">{{ $layananAdmin->deskripsi }}
                                        </p>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="" class="btn btn-sm btn-outline-warning px-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.layanan.destroy', $layananAdmin->id_layanan) }}"
                                                method="POST" onsubmit="return confirm('Yakin ingin mengarsipkan layanan ini?')"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            Menampilkan {{ $layanan->firstItem() ?? 0 }} sampai {{ $layanan->lastItem() ?? 0 }} dari total
                            {{ $layanan->total() }} entri
                        </p>
                    </div>
                    <div class="col-md-6">
                        <nav class="float-md-end" aria-label="Pagination">
                            {{ $layanan->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection