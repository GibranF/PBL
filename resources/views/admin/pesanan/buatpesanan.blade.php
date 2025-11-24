@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <h2 class="mb-4 text-primary">Buat Pesanan Baru (Admin)</h2>

        @if ($errors->any())
            <div class="alert alert-danger rounded-2 shadow-sm">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pesanan.store') }}" method="POST" class="card p-4 shadow-sm bg-white rounded-2">
            @csrf

            <input type="hidden" name="id_user" value="{{ Auth::id() }}" />

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="nama_pelanggan" class="form-label fw-semibold text-primary">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" id="nama_pelanggan"
                        class="form-control form-control-lg rounded-2" style="border-color: pink;"
                        value="{{ old('nama_pelanggan') }}" required placeholder="Masukkan nama pelanggan" />
                </div>
                <div class="col-md-6">
                    <label for="nomor_hp" class="form-label fw-semibold text-primary">Nomor HP</label>
                    <input type="tel" name="nomor_hp" id="nomor_hp" class="form-control form-control-lg rounded-2"
                        style="border-color: pink;" value="{{ old('nomor_hp') }}" required placeholder="08xxxxxxx" />
                </div>
                <div class="col-12">
                    <label for="alamat" class="form-label fw-semibold text-primary">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="form-control form-control-lg rounded-2"
                        style="border-color: pink;" placeholder="Masukkan alamat lengkap"
                        required>{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div id="layanan-container" class="row g-4">
                @if ($layanan->isEmpty())
                    <div class="col-12 text-center text-muted fs-5">Layanan belum tersedia.</div>
                @else
                    <div class="layanan-box col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-primary">Pilih Layanan</label>

                            <select name="layanan[0][id_layanan]"
                                class="form-select form-select-lg rounded-3 layanan select-responsive" required>

                                <option value="" selected disabled class="text-muted">
                                    🔽 Pilih layanan
                                </option>

                                @foreach ($layanan as $service)
                                    <option value="{{ $service->id_layanan }}" data-price="{{ $service->harga }}"
                                        data-satuan="{{ $service->satuan }}">
                                        {{ $service->nama_layanan }} ({{ $service->satuan ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-primary">Harga</label>
                            <input type="text" class="form-control form-control-lg rounded-2 harga-display" readonly
                                placeholder="Rp 0" style="border-color: pink;" />
                            <input type="hidden" name="layanan[0][harga]" class="harga" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-primary">Jumlah (Kg/M²/Pcs)</label>
                            <input type="number" name="layanan[0][dimensi]"
                                class="form-control form-control-lg rounded-2 dimensi" style="border-color: pink;" step="0.01"
                                min="0.01" required placeholder="Contoh: 1.5" />
                            <small class="text-muted">Gunakan titik (.) sebagai pemisah desimal</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-primary">Satuan</label>
                            <input type="text" name="layanan[0][satuan]" class="form-control form-control-lg rounded-2 satuan"
                                style="border-color: pink;" readonly>
                        </div>

                    </div>
                @endif
            </div>

            <button type="button" id="add-layanan" class="btn btn-outline-primary rounded-2 my-3" {{ $layanan->isEmpty() ? 'disabled' : '' }}>
                <i class="bi bi-plus-lg"></i> Tambah Layanan
            </button>

            <div class="row g-4 mt-4">
                <div class="col-md-6">
                    <label for="antar_jemput" class="form-label fw-semibold text-primary">Layanan Antar Jemput</label>
                    <select name="antar_jemput" id="antar_jemput" class="form-select form-select-lg rounded-2"
                        style="border-color: pink;">
                        <option value="no">Tidak</option>
                        <option value="yes">Ya</option>
                    </select>
                </div>

                <div class="col-md-6 d-flex align-items-center">
                    <a href="https://www.google.com/maps/dir/?api=1&origin=My+Location&destination=Istana+Laundry+Banyuwangi"
                        target="_blank" class="text-decoration-none text-primary fw-semibold">
                        <i class="bi bi-geo-alt-fill me-1"></i> Cek Jarak ke Istana Laundry
                    </a>
                </div>

                <div class="col-md-6">
                    <label for="jarak_km" class="form-label fw-semibold text-primary">Jarak (Km)</label>
                    <input type="number" name="jarak_km" id="jarak_km" class="form-control form-control-lg rounded-2"
                        style="border-color: pink;" step="0.1" min="0" value="{{ old('jarak_km') }}"
                        placeholder="Contoh: 5.2" />
                </div>
            </div>

            <div class="mt-4">
                <label for="total" class="form-label fw-semibold text-primary">Total Biaya</label>
                <input type="text" id="total" class="form-control form-control-lg rounded-2 bg-light"
                    style="border-color: pink;" readonly value="Rp 0" />
            </div>

            <fieldset class="mt-4">
                <legend class="fw-semibold text-primary mb-3">Pilihan Pembayaran</legend>
                <label>Pilihan Pembayaran</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pembayaran_option" id="bayar_online"
                        value="bayar_online" checked>
                    <label class="form-check-label" for="bayar_online">Bayar Online</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pembayaran_option" id="bayar_offline"
                        value="bayar_offline">
                    <label class="form-check-label" for="bayar_offline">Bayar Offline (Cash)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pembayaran_option" id="bayar_nanti"
                        value="bayar_nanti">
                    <label class="form-check-label" for="bayar_nanti">Bayar Nanti</label>
                </div>
            </fieldset>
            <button type="submit" class="btn btn-primary btn-lg rounded-2 w-100 mt-4 shadow-sm">
                <i class="bi bi-cart-check me-2"></i> Lanjutkan Ke Pembayaran
            </button>
        </form>
    </div>

    {{-- SCRIPT TETAP SAMA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const layananContainer = document.getElementById('layanan-container');
            const addLayananBtn = document.getElementById('add-layanan');
            const totalInput = document.getElementById('total');

            if (!layananContainer || !addLayananBtn || !totalInput) return;

            function setupLayananEvents(container) {
                const layananSelect = container.querySelector('.layanan');
                const hargaInput = container.querySelector('.harga');
                const hargaDisplay = container.querySelector('.harga-display');
                const dimensiInput = container.querySelector('.dimensi');

                if (layananSelect && hargaInput && hargaDisplay && dimensiInput) {
                    layananSelect.addEventListener('change', () => {
                        const option = layananSelect.options[layananSelect.selectedIndex];
                        const price = parseFloat(option.getAttribute('data-price')) || 0;
                        hargaInput.value = price;
                        hargaDisplay.value = price.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                        hitungTotal();
                    });

                    dimensiInput.addEventListener('input', hitungTotal);
                }
            }

            function hitungTotal() {
                let total = 0;
                const layananBoxes = layananContainer.querySelectorAll('.layanan-box');

                layananBoxes.forEach(box => {
                    const harga = parseFloat(box.querySelector('.harga').value) || 0;
                    const dimensi = parseFloat(box.querySelector('.dimensi').value) || 0;
                    total += harga * dimensi;
                });

                const antarJemput = document.getElementById('antar_jemput').value;
                const jarakKm = parseFloat(document.getElementById('jarak_km').value) || 0;

                if (antarJemput === 'yes' && jarakKm > 0) {
                    total += jarakKm * 5000;
                }

                totalInput.value = total.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
            }

            addLayananBtn.addEventListener('click', () => {
                const index = layananContainer.querySelectorAll('.layanan-box').length;
                const newLayanan = document.createElement('div');
                newLayanan.classList.add('layanan-box', 'col-md-6');

                newLayanan.innerHTML = `
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-primary">Pilih Layanan</label>
                                    <select name="layanan[${index}][id_layanan]" class="form-select form-select-lg rounded-2 layanan" style="border-color: pink;" required>
                                        <option value="" selected disabled>Pilih layanan</option>
                                        @foreach ($layanan as $service)
                                            <option value="{{ $service->id_layanan }}" data-price="{{ $service->harga }}">{{ $service->nama_layanan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-primary">Harga</label>
                                    <input type="text" class="form-control form-control-lg rounded-2 harga-display" readonly placeholder="Rp 0" style="border-color: pink;" />
                                    <input type="hidden" name="layanan[${index}][harga]" class="harga" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-primary">Jumlah (Kg/M²/Pcs)</label>
                                    <input type="number" name="layanan[${index}][dimensi]" class="form-control form-control-lg rounded-2 dimensi" style="border-color: pink;" step="0.01" min="0.01" required placeholder="Contoh: 1.5" />
                                    <small class="text-muted">Gunakan titik (.) sebagai pemisah desimal</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-primary">Satuan</label>
                                    <select name="layanan[${index}][satuan]" class="form-select form-select-lg rounded-2 satuan" style="border-color: pink;" required>
                                        <option value="kg">Kilogram</option>
                                        <option value="m2">Meter persegi</option>
                                        <option value="pcs">Pcs</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-2 remove-layanan">
                                    <i class="bi bi-x-lg"></i> Hapus Layanan
                                </button>
                            `;

                layananContainer.appendChild(newLayanan);
                setupLayananEvents(newLayanan);

                newLayanan.querySelector('.remove-layanan').addEventListener('click', () => {
                    newLayanan.remove();
                    hitungTotal();
                });
            });

            layananContainer.querySelectorAll('.layanan-box').forEach(setupLayananEvents);

            document.getElementById('antar_jemput').addEventListener('change', hitungTotal);
            document.getElementById('jarak_km').addEventListener('input', hitungTotal);

            hitungTotal();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function updateSatuan(selectElement) {
                let satuan = selectElement.options[selectElement.selectedIndex].dataset.satuan;
                let satuanInput = selectElement.closest('.layanan-box').querySelector('.satuan');
                satuanInput.value = satuan || '-';
            }

            // Untuk item layanan pertama
            document.querySelectorAll('.layanan').forEach(function (select) {
                updateSatuan(select);
                select.addEventListener('change', function () {
                    updateSatuan(this);
                });
            });

            // Untuk layanan tambahan
            document.getElementById('add-layanan').addEventListener('click', function () {
                setTimeout(() => {
                    document.querySelectorAll('.layanan').forEach(function (select) {
                        select.addEventListener('change', function () {
                            updateSatuan(this);
                        });
                    });
                }, 300);
            });

        });
    </script>
@endsection