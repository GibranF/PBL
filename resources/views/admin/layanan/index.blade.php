@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="card component-page">
            <div class="card-body">
                <!-- Header -->
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

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center" style="min-width: 800px">
                        <thead class="table-light text-center">
                            <tr>
                                <th>ID</th>
                                <th>Nama Layanan</th>
                                <th>Harga</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($layanan as $layananAdmin)
                                <tr>
                                    <td class="fw-bold">{{ $layananAdmin->id_layanan }}</td>
                                    <td>{{ $layananAdmin->nama_layanan }}</td>
                                    <td>Rp {{ number_format($layananAdmin->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <p class="mb-0 deskripsi-text text-truncate"
                                            style="max-width: 600px; cursor: pointer; overflow: hidden; white-space: nowrap;"
                                            title="Klik untuk melihat selengkapnya">
                                            {{ $layananAdmin->deskripsi }}
                                        </p>
                                    </td>

                                    <td>
                                        <div class="btn-group">
                                            <a href="" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.layanan.destroy', $layananAdmin->id_layanan) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin mengarsipkan layanan ini?')"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
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

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            Menampilkan {{ $layanan->firstItem() ?? 0 }} sampai {{ $layanan->lastItem() ?? 0 }} dari total
                            {{ $layanan->total() }} entri
                        </p>
                    </div>
                    <div class="col-md-6">
                        {{ $layanan->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".deskripsi-text").forEach(function(el) {
                el.addEventListener("click", function() {
                    if (el.classList.contains("expanded")) {
                        // kembali truncate
                        el.classList.remove("expanded");
                        el.classList.add("text-truncate");
                        el.style.whiteSpace = "nowrap";
                        el.style.overflow = "hidden";
                    } else {
                        // tampilkan full
                        el.classList.add("expanded");
                        el.classList.remove("text-truncate");
                        el.style.whiteSpace = "normal";
                        el.style.overflow = "visible";
                    }
                });
            });
        });
    </script>
@endpush
