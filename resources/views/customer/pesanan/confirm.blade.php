@extends('layouts.cust')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white">
                    <h4 class="mb-0">Konfirmasi Pesanan</h4>
                </div>
                <div class="card-body text-center py-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <i class="fab fa-whatsapp fa-4x text-success mb-3"></i>
                    <h3>Pesanan Berhasil Dibuat!</h3>
                    <div class="mt-4">
                        <a href="{{ route('customer.pesanan.index') }}" class="btn btn-outline-secondary">
                            Lihat Daftar Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-buka WhatsApp setelah 1 detik
    setTimeout(function() {
        window.open("{{ $whatsappLink }}", "_blank");
    }, 1000);
</script>
@endsection