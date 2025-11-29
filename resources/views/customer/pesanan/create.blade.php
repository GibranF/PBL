@extends('layouts.cust')

@section('title', 'Form Pesanan')
@section('content')
    <section id="transaksi" class="section">
        <div class="header-section">
            <h1 class="section-title">Buat Pesanan Baru</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customer.pesanan.store') }}" method="POST" class="order-form">
            @csrf

            <!-- Customer Info -->
            <div class="form-section">
                <h3 class="form-section-title">Data Pelanggan</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" value="{{ $user->alamat }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" class="form-control" value="{{ $user->nomor_hp }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="form-section">
                <h3 class="form-section-title">Pilih Layanan</h3>

                @if ($layanan->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Layanan belum tersedia</p>
                    </div>
                @else
                    <div id="layanan-container">
                        <div class="service-card">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Pilih Layanan</label>
                                    <select name="layanan[0][id_layanan]"
                                        class="form-select form-select-lg rounded-2 service-select" required>
                                        <option value="" disabled {{ $selectedLayanan ? '' : 'selected' }}>
                                            Pilih layanan
                                        </option>

                                        @foreach ($layanan as $service)
                                            <option value="{{ $service->id_layanan }}" data-satuan="{{ $service->satuan }}"
                                                data-price="{{ $service->harga }}"
                                                {{ $selectedLayanan == $service->id_layanan ? 'selected' : '' }}>
                                                {{ $service->nama_layanan }} ({{ $service->satuan }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="text" class="form-control price-display" readonly>
                                    <input type="hidden" name="layanan[0][harga]" class="price-input">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Kg/Meter persegi/Satuan</label>
                                    <input type="number" name="layanan[0][dimensi]" class="form-control quantity-input"
                                        step="0.01" required placeholder="Contoh: 1.5">
                                    <small class="form-text">Gunakan titik (.)</small>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" name="layanan[0][satuan]"
                                        class="form-control form-control-lg rounded-2 satuan" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-service" class="btn btn-secondary"
                        {{ $layanan->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-plus"></i> Tambah Layanan
                    </button>
                @endif
            </div>

            <!-- Delivery -->
            <div class="form-section">
                <h3 class="form-section-title">Layanan Antar Jemput</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Pilihan Antar Jemput</label>
                        <select name="antar_jemput" class="form-control" id="antar-jemput-select">
                            <option value="no">Tidak</option>
                            <option value="antar">Antar Saja</option>
                            <option value="jemput">Jemput Saja</option>
                            <option value="yes">Antar & Jemput</option>
                        </select>
                    </div>
                    <div class="form-group" id="jarak-group">
                        <label>Jarak (Km)</label>
                        <input type="number" name="jarak_km" id="jarak-input" class="form-control" step="0.1"
                            value="{{ old('jarak_km', 0) }}">
                        <small class="form-text">
                            <a href="https://www.google.com/maps/dir/?api=1&origin=My+Location&destination=Istana+Laundry+Banyuwangi"
                                target="_blank">
                                Cek jarak ke lokasi kami
                            </a>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="form-section">
                <h3 class="form-section-title">Pembayaran</h3>
                <div class="form-group">
                    <label class="total-label">Total Biaya</label>
                    <div class="total-amount" id="total-display">Rp 0</div>
                    <input type="hidden" name="total" id="total-input">
                </div>

                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" name="pembayaran_option" id="bayar_online" value="bayar_online" checked>
                        <label for="bayar_online">
                            <i class="fas fa-credit-card"></i>
                            <span>Bayar Online</span>
                            <small>Transfer bank sekarang</small>
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="pembayaran_option" id="bayar_offline" value="bayar_offline">
                        <label for="bayar_offline">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Bayar Offline</span>
                            <small>Bayar tunai saat penjemputan</small>
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="pembayaran_option" id="bayar_nanti" value="bayar_nanti">
                        <label for="bayar_nanti">
                            <i class="fas fa-clock"></i>
                            <span>Bayar Nanti</span>
                            <small>Pilih metode pembayaran nanti</small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Lanjutkan
                    <small>
                        <i class="fas fa-info-circle"></i>
                        Setelah mengklik "Lanjutkan", Anda akan diarahkan untuk mengirim pesan WhatsApp ke admin dengan
                        detail pesanan.
                    </small>
                </button>
            </div>
        </form>
    </section>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
        }

        .order-form {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .form-section-title {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-row .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #7d2ae8;
            box-shadow: 0 0 0 3px rgba(125, 42, 232, 0.1);
        }

        .service-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        #add-service {
            margin-top: 10px;
        }

        .total-label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #7d2ae8;
            margin-bottom: 20px;
        }

        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .payment-option {
            display: flex;
            align-items: center;
        }

        .payment-option input[type="radio"] {
            margin-right: 15px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            outline: none;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
        }

        .payment-option input[type="radio"]:checked {
            border-color: #7d2ae8;
            background-color: #7d2ae8;
        }

        .payment-option input[type="radio"]:checked::before {
            content: '';
            display: block;
            width: 10px;
            height: 10px;
            background-color: white;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .payment-option label {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            background: #f9f9f9;
            flex: 1;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option label:hover {
            background: #f0f0f0;
        }

        .payment-option input:checked+label {
            background: #7d2ae8;
            color: white;
        }

        .payment-option i {
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .payment-option span {
            font-weight: 500;
            margin-right: 10px;
        }

        .payment-option small {
            opacity: 0.8;
            font-size: 0.85rem;
        }

        .payment-option input:checked+label small {
            opacity: 0.9;
        }

        .form-actions {
            margin-top: 30px;
            text-align: right;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }

        .btn-primary {
            background-color: #7d2ae8;
            border: none;
        }

        .btn-primary:hover {
            background-color: #5b13b9;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 8px 15px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #666;
            background: #f0f0f0;
            border-radius: 10px;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #ccc;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }

            .form-row .form-group {
                min-width: 100%;
            }

            .payment-option label {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
                padding: 10px;
            }

            .payment-option i {
                margin-right: 0;
                margin-bottom: 5px;
            }

            .payment-option small {
                margin-left: 0;
                display: block;
            }

            .form-actions {
                text-align: center;
            }

            .btn-primary {
                width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const layananContainer = document.getElementById('layanan-container');
            const addServiceBtn = document.getElementById('add-service');
            const totalDisplay = document.getElementById('total-display');
            const totalInput = document.getElementById('total-input');
            const antarSelect = document.getElementById('antar-jemput-select');
            const jarakInput = document.getElementById('jarak-input');
            const jarakGroup = document.getElementById('jarak-group');

            // Fungsi untuk menghitung biaya antar jemput
            function hitungBiayaAntarJemput(jarak_km, jenis) {
                let biayaFull = 0;

                if (jarak_km <= 3) {
                    biayaFull = 0; // Gratis 3 km pertama
                } else {
                    biayaFull = (jarak_km - 3) * 5000; // Rp 5000 per km setelah 3 km
                }

                // Harga setengah jika antar saja atau jemput saja
                if (jenis === 'antar' || jenis === 'jemput') {
                    return biayaFull / 2;
                }

                // Harga penuh jika antar & jemput
                if (jenis === 'yes') {
                    return biayaFull;
                }

                return 0; // Jika tidak
            }

            // Format mata uang
            function formatCurrency(amount) {
                return 'Rp ' + Number(amount).toLocaleString('id-ID');
            }

            // Inisialisasi layanan pertama jika ada
            if (layananContainer && layananContainer.querySelector('.service-card')) {
                setupServiceEvents(layananContainer.querySelector('.service-card'));
                calculateTotal();
            }

            // Tambah layanan baru
            if (addServiceBtn) {
                addServiceBtn.addEventListener('click', function() {
                    const index = layananContainer.querySelectorAll('.service-card').length;
                    const newService = document.createElement('div');
                    newService.className = 'service-card';
                    newService.innerHTML = `
                        <div class="form-row">
                            <div class="form-group">
                                <label>Pilih Layanan</label>
                                <select name="layanan[${index}][id_layanan]" class="form-control service-select" required>
                                    <option value="">Pilih Layanan</option>
                                    @foreach ($layanan as $service)
                                        <option value="{{ $service->id_layanan }}" data-price="{{ $service->harga }}" data-satuan="{{ $service->satuan }}">
                                            {{ $service->nama_layanan }} ({{ $service->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="text" class="form-control price-display" readonly>
                                <input type="hidden" name="layanan[${index}][harga]" class="price-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Jumlah/Dimensi</label>
                                <input type="number" name="layanan[${index}][dimensi]" class="form-control quantity-input"
                                    step="0.01" required placeholder="Contoh: 1.5">
                                <small class="form-text">Gunakan titik (.)</small>
                            </div>
                            <div class="form-group">
                                <label>Satuan</label>
                                <input type="text" name="layanan[${index}][satuan]" class="form-control satuan" readonly>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-service">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    `;
                    layananContainer.appendChild(newService);
                    setupServiceEvents(newService);
                });
            }

            // Setup event listeners untuk setiap card layanan
            function setupServiceEvents(card) {
                const select = card.querySelector('.service-select');
                const priceDisplay = card.querySelector('.price-display');
                const priceInput = card.querySelector('.price-input');
                const quantityInput = card.querySelector('.quantity-input');
                const satuanInput = card.querySelector('.satuan');

                if (select) {
                    select.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                        const defaultUnit = selectedOption.getAttribute('data-satuan') || '-';

                        if (priceInput) priceInput.value = price;
                        if (priceDisplay) priceDisplay.value = formatCurrency(price);
                        if (satuanInput) satuanInput.value = defaultUnit;

                        calculateTotal();
                    });
                }

                if (quantityInput) {
                    quantityInput.addEventListener('input', calculateTotal);
                }

                const removeBtn = card.querySelector('.remove-service');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (layananContainer.querySelectorAll('.service-card').length > 1) {
                            card.remove();
                            calculateTotal();
                        } else {
                            alert('Minimal harus ada satu layanan.');
                        }
                    });
                }

                // Trigger awal jika ada select yang sudah terpilih
                if (select && select.value) {
                    setTimeout(() => select.dispatchEvent(new Event('change')), 10);
                } else {
                    if (priceInput) priceInput.value = 0;
                    if (priceDisplay) priceDisplay.value = formatCurrency(0);
                    if (satuanInput && !satuanInput.value) satuanInput.value = '-';
                }
            }

            // Event listener untuk opsi antar jemput
            if (antarSelect && jarakInput && jarakGroup) {
                antarSelect.addEventListener('change', function() {
                    if (this.value !== 'no') {
                        jarakGroup.style.display = 'block';
                        jarakInput.setAttribute('required', 'required');
                    } else {
                        jarakGroup.style.display = 'none';
                        jarakInput.removeAttribute('required');
                        jarakInput.value = 0;
                    }
                    calculateTotal();
                });

                jarakInput.addEventListener('input', calculateTotal);

                // Set initial visibility
                setTimeout(() => antarSelect.dispatchEvent(new Event('change')), 10);
            }

            // Fungsi utama untuk menghitung total keseluruhan
            function calculateTotal() {
                let subtotalLayanan = 0;

                document.querySelectorAll('.service-card').forEach(card => {
                    const price = parseFloat(card.querySelector('.price-input')?.value) || 0;
                    const quantity = parseFloat(card.querySelector('.quantity-input')?.value) || 0;
                    subtotalLayanan += price * quantity;
                });

                let biayaAntar = 0;
                const jenisAntar = antarSelect?.value || 'no';
                const distance = parseFloat(jarakInput?.value) || 0;

                if (jenisAntar !== 'no') {
                    biayaAntar = hitungBiayaAntarJemput(distance, jenisAntar);
                }

                const totalAkhir = subtotalLayanan + biayaAntar;

                if (totalDisplay) totalDisplay.textContent = formatCurrency(totalAkhir);
                if (totalInput) totalInput.value = totalAkhir;
            }

            // Panggil setup untuk semua card yang sudah ada
            document.querySelectorAll('.service-card').forEach(setupServiceEvents);

            // Panggil calculateTotal() saat halaman dimuat
            setTimeout(calculateTotal, 20);
        });
    </script>
@endsection