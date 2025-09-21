@extends('layouts.admin')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/updatepesanan.css') }}">
@endsection

@section('content')
    <div class="container py-4">
        <h4 class="mb-3 text-primary" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
            Update Status Pesanan
        </h4>

        <div class="card p-4 shadow-sm updatepesanan bg-white rounded-2">
            <form action="{{ route('admin.pesanan.updateStatus', $transaksi->id_transaksi) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="id_transaksi" class="form-label text-primary fw-semibold">ID Transaksi</label>
                    <input type="text" class="form-control form-control-lg rounded-2" style="border-color: pink;"
                        value="{{ $transaksi->id_transaksi }}" disabled>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label text-primary fw-semibold">Status Pesanan</label>
                    <select name="status" id="status" class="form-select form-select-lg rounded-2"
                        style="border-color: pink;" required>
                        <option value="belum diproses" {{ $transaksi->status == 'belum diproses' ? 'selected' : '' }}>
                            Belum Diproses
                        </option>
                        <option value="pesanan diproses" {{ $transaksi->status == 'pesanan diproses' ? 'selected' : '' }}>
                            Pesanan Diproses
                        </option>
                        <option value="pesanan selesai" {{ $transaksi->status == 'pesanan selesai' ? 'selected' : '' }}>
                            Pesanan Selesai
                        </option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg rounded-2 shadow-sm">
                        <i class="fas fa-save me-2"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection