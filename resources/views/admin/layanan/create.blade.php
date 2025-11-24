@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center rounded-top-3">
                <h4 class="mb-0 text-purple"><i class="fas fa-plus-circle me-2"></i> Tambah Layanan Baru</h4>
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-sm btn-secondary rounded px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger rounded-2">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle text-danger me-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- form upload gambar harus pakai enctype -->
                <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data"
                    class="row g-3 mt-3">
                    @csrf

                    <div class="col-md-6">
                        <label for="nama_layanan" class="form-label">Nama Layanan</label>
                        <input type="text" name="nama_layanan" id="nama_layanan" class="form-control rounded-2 shadow-sm"
                            placeholder="Contoh: Cuci Karpet" value="{{ old('nama_layanan') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" name="harga" id="harga" class="form-control rounded-2 shadow-sm"
                            placeholder="Contoh: 50000" value="{{ old('harga') }}" required min="0"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '')" />
                    </div>

                    <div class="col-md-12">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control rounded-2 shadow-sm"
                            placeholder="Contoh: Pembersihan karpet menggunakan metode dry clean..."
                            required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control rounded-2 shadow-sm"
                            placeholder="Contoh: kg, pcs, per set" value="{{ old('satuan') }}">
                    </div>


                    <!-- input file gambar -->
                    <div class="col-md-12">
                        <label for="gambar" class="form-label">Gambar Layanan</label>
                        <p class="text-muted" style="font-size: 0.9em;">*Disarankan ukuran gambar: 600 x 700 px</p>
                        <input type="file" name="gambar" id="gambar" class="form-control rounded-2 shadow-sm"
                            accept="image/*">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary rounded px-4 shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Layanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection