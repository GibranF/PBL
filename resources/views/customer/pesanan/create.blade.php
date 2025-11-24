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
    <label class=>Pilih Layanan</label>
    <select name="layanan[0][id_layanan]" 
        class="form-select form-select-lg rounded-2 layanan" required>

    <option value="" disabled {{ $selectedLayanan ? '' : 'selected' }}>
        Pilih layanan
    </option>

    @foreach ($layanan as $service)
        <option 
            value="{{ $service->id_layanan }}"
            data-satuan="{{ $service->satuan }}"
            data-price="{{ $service->harga }}"
            {{ ($selectedLayanan == $service->id_layanan) ? 'selected' : '' }}
        >
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
    <input type="text" 
           name="layanan[0][satuan]" 
           class="form-control form-control-lg rounded-2 satuan" 
           readonly>
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
                        <select name="antar_jemput" class="form-control">
                            <option value="no">Tidak</option>
                            <option value="yes">Ya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jarak (Km)</label>
                        <input type="number" name="jarak_km" class="form-control" step="0.1"
                            value="{{ old('jarak_km') }}">
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
            /* sesuaikan dengan tinggi navbar */
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
            width: 100%; /* Menambahkan ini untuk memastikan input penuh */
            box-sizing: border-box; /* Pastikan padding tidak menambah lebar total */
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
            /* style default radio */
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
            flex-shrink: 0; /* Mencegah radio mengecil */
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

        .payment-option input:checked + label {
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

        .payment-option input:checked + label small {
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
            text-decoration: none; /* Untuk tombol yang juga bisa jadi link */
            color: white; /* Default color untuk tombol */
        }

        .btn-primary {
            background-color: #7d2ae8;
            border: none;
        }

        .btn-primary:hover {
            background-color: #5b13b9;
        }

        .btn-secondary {
            background-color: #6c757d; /* Warna abu-abu */
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #dc3545; /* Warna merah */
            border: none;
            color: white;
            padding: 8px 15px; /* Lebih kecil dari btn utama */
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

        /* Responsive adjustments */
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

            // Fungsi untuk menghitung total biaya antar jemput
            function hitungBiayaAntarJemput(jarak_km) {
                if (jarak_km <= 3) {
                    return 0; // Gratis untuk 3 KM pertama
                } else {
                    return (jarak_km - 3) * 5000; // Rp 5.000 per KM setelah 3 KM pertama
                }
            }

            // Inisialisasi layanan pertama jika ada
            if (layananContainer.querySelector('.service-card')) {
                setupServiceEvents(layananContainer.querySelector('.service-card'));
                calculateTotal(); // Hitung total awal
            }

            // Tambah layanan baru
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
                                        {{ $service->nama_layanan }}
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
                            <select name="layanan[${index}][satuan]" class="form-control unit-select" required>
                                <option value="kg">Kilogram</option>
                                <option value="m2">Meter persegi</option>
                                <option value="pcs">Pcs</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-service">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                `;
                layananContainer.appendChild(newService);
                setupServiceEvents(newService);
            });

            // Setup event listeners untuk setiap card layanan baru atau yang sudah ada
            function setupServiceEvents(card) {
                const select = card.querySelector('.service-select');
                const priceDisplay = card.querySelector('.price-display');
                const priceInput = card.querySelector('.price-input');
                const quantityInput = card.querySelector('.quantity-input');
                const unitSelect = card.querySelector('.unit-select'); // Tambahkan ini jika satuan harus otomatis berdasarkan layanan

                // Fungsi untuk memperbarui harga dan satuan saat layanan dipilih
                select.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                    // Ambil satuan dari data-satuan di option, jika ada
                    const defaultUnit = selectedOption.getAttribute('data-satuan') || 'kg';

                    priceInput.value = price;
                    priceDisplay.value = formatCurrency(price);
                    unitSelect.value = defaultUnit; // Set satuan otomatis
                    calculateTotal();
                });

                // Perbarui total saat kuantitas berubah
                quantityInput.addEventListener('input', calculateTotal);
                
                // Tambahkan event listener untuk unitSelect jika perlu diperbarui
                unitSelect.addEventListener('change', calculateTotal);

                // Hapus layanan
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

                // Pemicu event change secara manual untuk menginisialisasi harga dan satuan
                // pada layanan yang sudah ada atau yang baru ditambahkan
                if (select.value) { // Jika ada layanan yang terpilih (misal old() value)
                    select.dispatchEvent(new Event('change'));
                } else {
                    // Jika belum ada layanan terpilih, atur harga dan satuan ke 0/default
                    priceInput.value = 0;
                    priceDisplay.value = formatCurrency(0);
                    unitSelect.value = 'kg'; // Default awal
                }
            }

            // Event listener untuk opsi antar jemput
            document.querySelector('[name="antar_jemput"]').addEventListener('change', function() {
                const jarakKmInput = document.querySelector('[name="jarak_km"]');
                if (this.value === 'yes') {
                    jarakKmInput.closest('.form-group').style.display = 'block';
                    jarakKmInput.setAttribute('required', 'required');
                } else {
                    jarakKmInput.closest('.form-group').style.display = 'none';
                    jarakKmInput.removeAttribute('required');
                    jarakKmInput.value = 0; // Reset jarak_km jika tidak pakai antar jemput
                }
                calculateTotal();
            });

            // Event listener untuk input jarak_km
            document.querySelector('[name="jarak_km"]').addEventListener('input', calculateTotal);

            // Fungsi utama untuk menghitung total keseluruhan
            function calculateTotal() {
                let subtotalLayanan = 0;

                // Hitung subtotal dari semua layanan
                document.querySelectorAll('.service-card').forEach(card => {
                    const price = parseFloat(card.querySelector('.price-input').value) || 0;
                    const quantity = parseFloat(card.querySelector('.quantity-input').value) || 0;
                    subtotalLayanan += price * quantity;
                });

                let biayaAntar = 0;
                const needsDelivery = document.querySelector('[name="antar_jemput"]').value === 'yes';
                const distance = parseFloat(document.querySelector('[name="jarak_km"]').value) || 0;

                if (needsDelivery) {
                    biayaAntar = hitungBiayaAntarJemput(distance);
                }

                const totalAkhir = subtotalLayanan + biayaAntar;

                // Perbarui tampilan total
                totalDisplay.textContent = formatCurrency(totalAkhir);
                totalInput.value = totalAkhir; // Pastikan hidden input juga terupdate
            }

            // Fungsi untuk format mata uang
            function formatCurrency(amount) {
                return 'Rp ' + amount.toLocaleString('id-ID');
            }

            // Panggil calculateTotal() saat halaman dimuat untuk inisialisasi total
            // dan mengatur tampilan jarak_km berdasarkan nilai default 'antar_jemput'
            document.querySelector('[name="antar_jemput"]').dispatchEvent(new Event('change'));
        });
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const layananSelect = document.querySelector(".layanan");
    const satuanInput = document.querySelector(".satuan");

    layananSelect.addEventListener("change", function () {
        const satuan = this.options[this.selectedIndex].getAttribute("data-satuan");

        if (satuan && satuan !== "") {
            satuanInput.value = satuan;
        } else {
            satuanInput.value = "-";
        }
    });
});
</script>
@endsection
