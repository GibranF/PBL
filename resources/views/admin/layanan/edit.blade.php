@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-body">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Edit Layanan</h2>
                    <p class="text-muted mb-0">Perbarui data layanan yang sudah ada</p>
                </div>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>

            <!-- Form Edit -->
            <form action="{{ route('admin.layanan.update', $layanan->id_layanan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Layanan -->
                <div class="mb-3">
                    <label for="nama_layanan" class="form-label">Nama Layanan</label>
                    <input type="text" class="form-control @error('nama_layanan') is-invalid @enderror"
                           id="nama_layanan" name="nama_layanan"
                           value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required>
                    @error('nama_layanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga -->
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control @error('harga') is-invalid @enderror"
                           id="harga" name="harga"
                           value="{{ old('harga', $layanan->harga) }}" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                              id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Satuan (opsional) -->
                <div class="mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control @error('satuan') is-invalid @enderror"
                           id="satuan" name="satuan"
                           value="{{ old('satuan', $layanan->satuan) }}">
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Gambar -->
                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Layanan</label>
                    @if($layanan->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $layanan->gambar) }}" alt="{{ $layanan->nama_layanan }}" width="120" class="img-thumbnail rounded">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                           id="gambar" name="gambar" accept="image/*">
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
