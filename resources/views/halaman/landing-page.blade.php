<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Island.com</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!-- Favicons -->
    <link href="assets/img/logo.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/layanan.css" rel="stylesheet">
    <link href="assets/css/navbar.css" rel="stylesheet">
    <link href="assets/css/slider2.css" rel="stylesheet">
</head>

<body class="index-page">
    <x-navbar/>
    <main class="main">

<!-- Hero Section -->
<section id="hero" class="hero section">

  <!-- Container Slide -->
  <div class="container-slide">
    <div class="slide">

<!-- Slide 1 -->
<div class="item side-slide" style="background-image: url('assets/img/karpet.png');">
  <div class="content">
    <div class="name">Cuci Karpet</div>
    <div class="des">Kami menjaga kualitas setiap cucian karpet agar tetap harum dan terawat, mengembalikan keindahan dan kebersihannya.</div>
    <button>Selengkapnya</button>
  </div>
</div>

<!-- Slide 2 -->
<div class="item side-slide" style="background-image: url('assets/img/koin.png');">
  <div class="content">
    <div class="name">Cuci Koin Mandiri</div>
    <div class="des">Nikmati layanan self-service yang cepat dan efisien dengan mesin cuci dan pengering modern kami.</div>
    <button>Selengkapnya</button>
  </div>
</div>

      <!-- Slide 3 -->
      <div class="item side-slide" style="background-image: url('assets/img/antarjemput.png');">
        <div class="content">
          <div class="name">Antar Jemput</div>
          <div class="des">Layanan antar jemput kami memudahkan Anda untuk mencuci pakaian tanpa perlu keluar rumah. Cucian dijemput dan diantar kembali dalam kondisi bersih dan rapi.</div>
          <button>Selengkapnya</button>
        </div>
      </div>

    </div>

    <!-- Tombol navigasi -->
    <div class="button">
      <button class="prev"><i class="fa-solid fa-arrow-left"></i></button>
      <button class="next"><i class="fa-solid fa-arrow-right"></i></button>
    </div>
  </div>
</section>



<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  let slides = document.querySelectorAll(".item");
  let slideContainer = document.querySelector(".slide");
  let pagination = document.querySelector(".button");

  let current = 0;
  let total = slides.length;
  let intervalTime = 8000; // 8 detik
  let autoSlide;

  // bikin dot pagination sesuai jumlah slide
  pagination.innerHTML = "";
  slides.forEach((_, index) => {
    let dot = document.createElement("button");
    if (index === 0) dot.classList.add("active");
    dot.addEventListener("click", () => goToSlide(index));
    pagination.appendChild(dot);
  });

  let dots = pagination.querySelectorAll("button");

  function showSlide(index) {
    // reset semua
    slides.forEach((s) => s.classList.remove("active"));
    dots.forEach((d) => d.classList.remove("active"));

    // aktifkan slide & dot
    slides[index].classList.add("active");
    dots[index].classList.add("active");

    current = index;
  }

  function nextSlide() {
    let next = (current + 1) % total;
    showSlide(next);
  }

  function goToSlide(index) {
    showSlide(index);
    resetAutoSlide();
  }

  function startAutoSlide() {
    autoSlide = setInterval(nextSlide, intervalTime);
  }

  function resetAutoSlide() {
    clearInterval(autoSlide);
    startAutoSlide();
  }

  // inisialisasi
  showSlide(0);
  startAutoSlide();
});

</script>


       <!-- HERO SECTION -->
<!-- MENGAPA HARUS ISTANA LAUNDRY -->
<section id="mengapa" class="py-5 text-white" 
  style="background: linear-gradient(135deg, #9b5de5, #f15bb5);">
  <div class="container" data-aos="fade-up">
    <div class="section-title text-center mb-5">
      <h2>Mengapa Harus <span class="text-warning">Istana Laundry?</span></h2>
      <p class="text-white">
        Istana Laundry adalah solusi laundry terpercaya di sekitar Anda dengan layanan profesional dan standar tinggi.
      </p>
    </div>
    <div class="row text-center gy-4">
      <!-- Mesin Aktif -->
      <div class="col-lg-3 col-md-6">
        <div class="p-4 rounded-4 h-100 shadow bg-white text-secondary">
          <div class="mb-3">
            <i class="bi bi-gear" style="font-size:2rem; color:#9b5de5;"></i>
          </div>
          <h5 class="fw-bold text-dark">10 Mesin Aktif</h5>
          <p class="mb-0">8 untuk baju, 2 untuk karpet</p>
        </div>
      </div>

      <!-- Jam Operasional -->
      <div class="col-lg-3 col-md-6">
        <div class="p-4 rounded-4 h-100 shadow bg-white text-secondary">
          <div class="mb-3">
            <i class="bi bi-clock-history" style="font-size:2rem; color:#9b5de5;"></i>
          </div>
          <h5 class="fw-bold text-dark">14 Jam Operasional</h5>
          <p class="mb-0">Setiap hari 07.00 - 21.00</p>
        </div>
      </div>

      <!-- Notifikasi -->
      <div class="col-lg-3 col-md-6">
        <div class="p-4 rounded-4 h-100 shadow bg-white text-secondary">
          <div class="mb-3">
            <i class="bi bi-bell" style="font-size:2rem; color:#9b5de5;"></i>
          </div>
          <h5 class="fw-bold text-dark">Notifikasi Pesanan</h5>
          <p class="mb-0">Langsung ke pemilik usaha</p>
        </div>
      </div>

      <!-- Pembayaran -->
      <div class="col-lg-3 col-md-6">
        <div class="p-4 rounded-4 h-100 shadow bg-white text-secondary">
          <div class="mb-3">
            <i class="bi bi-cash-coin" style="font-size:2rem; color:#9b5de5;"></i>
          </div>
          <h5 class="fw-bold text-dark">Pembayaran Mudah</h5>
          <p class="mb-0">QRIS, Transfer, dan Tunai</p>
        </div>
      </div>
    </div>
  </div>
</section>
<hr style="border: 1px solid #ccc; margin: 0;">


        <!-- Features Section -->
<section id="features" class="features section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Layanan Kami</h2>
        <p>Geser untuk melihat semua layanan!</p>
    </div>

    @php
        $layanan = App\Models\Layanan::all();
        $totalLayanan = $layanan->count();
    @endphp

    @if ($totalLayanan == 0)
        <div class="container text-center" data-aos="fade-up" data-aos-delay="100">
            <p style="font-size: 1.2rem; color: #666;">Layanan belum ditambahkan.</p>
        </div>
    @else
        @php
            $jumlahAtas = min(20, $totalLayanan); // Ambil 20 atau total jika kurang dari 20
            $barisAtas = $layanan->take($jumlahAtas);
            $barisBawah = $layanan->slice($jumlahAtas);
        @endphp

        <!-- Baris Atas -->
         
        <div class="container service-container service-container-top" data-aos="fade-up" data-aos-delay="100">
            <div class="service-wrapper">
                @foreach ($barisAtas as $layanan)
                    <div class="service-card-wrapper">
                        <div class="card service-card" style="width: 18rem; border-radius: 16px; overflow: hidden;">
                            <!-- 👇 Gambar statis -->
                              @php
                $gambar = match($layanan->nama_layanan) {
                    'Cuci Kering' => 'cucikering.png',
                    'Setrika' => 'setrika.png',
                    'Cuci Lipat' => 'cucilipat.png',
                    'Testing' => 'testing.png',
                    default => 'default.png'
                };
            @endphp

            <img src="{{ asset('assets/img/' . $gambar) }}"
                 class="card-img-top"
                 alt="{{ $layanan->nama_layanan }}"
                 style="height: 180px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $layanan->nama_layanan }}</h5>
                                <p class="card-text">{{ $layanan->deskripsi }}</p>
                                <button class="btn-see-more">Lihat Selengkapnya</button>
                                <p class="card-price">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                                <a href="{{ route('customer.pesanan.create', $layanan->id_layanan) }}"
                                    class="btn btn-primary w-100">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Baris Bawah -->
        <div class="container service-container service-container-bottom mt-4" data-aos="fade-up" data-aos-delay="100">
            <div class="service-wrapper">
                @foreach ($barisBawah as $layanan)
                    <div class="service-card-wrapper">
                        <div class="card service-card" style="width: 18rem; border-radius: 16px; overflow: hidden;">
                            <!-- 👇 Gambar statis -->
                            <img src="{{ asset('assets/img/laundry2.jpg') }}" class="card-img-top" alt="Laundry" style="height: 180px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $layanan->nama_layanan }}</h5>
                                <p class="card-text">{{ $layanan->deskripsi }}</p>
                                <button class="btn-see-more">Lihat Selengkapnya</button>
                                <p class="card-price">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                                <a href="{{ route('customer.pesanan.create', $layanan->id_layanan) }}"
                                    class="btn btn-primary w-100">Pesan Sekarang</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>

<!-- Call to Action Section -->
<section id="call-to-action" class="call-to-action section">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="cta-card p-5 rounded-4 position-relative overflow-hidden">
                <div class="cta-content position-relative z-index-2">
                    <h2 class="display-5 mb-3 fw-bold text-white">Promo Spesial Pembelian Pertama!</h2>
                    <p class="mb-4 fs-5">Nikmati layanan antar jemput gratis untuk semua area yang lebih dari 3 KM!</p>
                    <a href="{{ route('customer.pesanan.create') }}"
                        class="btn btn-light btn-lg px-4 py-2 rounded-pill fw-bold">
                        <i class="bi bi-lightning-charge-fill me-2"></i> Pesan Sekarang
                    </a>
                </div>
                <div class="cta-shapes">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                </div>
            </div>
        </div>
    </div>
</section>

        <!-- Stats Section -->
        <section id="stats" class="stats section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="1250" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Pelanggan Puas</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="3400" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Pakaian Dicuci</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="120" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Jam Operasional / Minggu</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Staf Profesional</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="contact section light-background">
            <div class="container section-title" data-aos="fade-up">
                <h2>Contact</h2>
                <p>Silakan hubungi kami melalui informasi di bawah ini.</p>
            </div>
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4 g-lg-5">
                    <div class="col-lg-7">
                        <div class="map" data-aos="fade-up" data-aos-delay="200">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3947.8256933523703!2d114.19284647477191!3d-8.32011329171558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd155a2ca7d17f7%3A0x9c96269494a0f5ca!2sIstana%20Laundry%20Banyuwangi!5e0!3m2!1sid!2sid!4v1748879579702!5m2!1sid!2sid"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                title="Istana Laundry Temuguruh location map"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="info-box" data-aos="fade-up" data-aos-delay="200">
                            <h3>Informasi Kontak</h3>
                            <div class="info-item" data-aos="fade-up" data-aos-delay="300">
                                <div class="icon-box">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="content">
                                    <h4>Alamat</h4>
                                    <p>Jl. Sultan Agung, Krajan Wetan, Temuguruh, Kec. Sempu, Kabupaten Banyuwangi, Jawa
                                        Timur 68468</p>
                                </div>
                            </div>
                            <div class="info-item" data-aos="fade-up" data-aos-delay="400">
                                <div class="icon-box">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="content">
                                    <h4>Jam Operasional</h4>
                                    <p>Senin - Minggu: 07.00 - 21.00</p>
                                </div>
                            </div>
                            <div class="info-item" data-aos="fade-up" data-aos-delay="500">
                                <div class="icon-box">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="content">
                                    <h4>Email</h4>
                                    <p>_istanalaundry@gmail.com</p>
                                </div>
                            </div>
                            <div class="info-item" data-aos="fade-up" data-aos-delay="600">
                                <div class="icon-box">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <div class="content">
                                    <h4>Nomor HP</h4>
                                    <p>0852-3563-7429</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Komentar Section -->
        <section id="komentar" style="background-color: #fff; padding: 40px 0;">
            <div class="container" style="max-width: 700px;">
                <h3 style="text-align: center; color: #800080;">Rating dan Ulasan</h3>
                <p style="text-align: center; margin-bottom: 30px;">Tulis komentar dan rating Anda</p>

                {{-- Form Ulasan Baru --}}
                <form action="{{ route('ulasan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar</label>
                        <textarea name="komentar" id="komentar" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select name="rating" id="rating" class="form-select" required>
                            <option value="5">★★★★★</option>
                            <option value="4">★★★★</option>
                            <option value="3">★★★</option>
                            <option value="2">★★</option>
                            <option value="1">★</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Kirim</button>
                    </div>
                </form>

                {{-- Daftar Ulasan --}}
                <section style="padding: 40px 0;">
                    <h4>Ulasan Terbaru</h4>
                    @foreach (\App\Models\Ulasan::latest()->take(5)->get() as $ulasan)
                        <div class="border p-3 mb-3 rounded">
                            <strong>{{ $ulasan->user->name ?? 'User tidak ditemukan' }}</strong><br>
                            <span style="color: gold;">{{ str_repeat('★', $ulasan->rating) }}</span><br>
                            <p>{{ $ulasan->pesan }}</p>

                            {{-- Tombol untuk pemilik ulasan (edit & hapus) --}}
                            @if (Auth::check() && $ulasan->id_user == Auth::id())
                                {{-- Form Update --}}
                                <form action="{{ route('ulasan.update', $ulasan->id_ulasan) }}" method="POST" class="mb-2 mt-2">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="komentar" class="form-control" required>{{ $ulasan->pesan }}</textarea>
                                    <select name="rating" class="form-select mt-2" required>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ $ulasan->rating == $i ? 'selected' : '' }}>
                                                {{ str_repeat('★', $i) }}
                                            </option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="btn btn-warning btn-sm mt-2">Update</button>
                                </form>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('ulasan.destroy', ['id' => $ulasan->id_ulasan]) }}" method="POST">

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mt-1">Hapus</button>
                                </form>
                            @endif

                            {{-- Tombol hapus khusus admin (tidak bisa edit) --}}
                            @if (Auth::check() && Auth::user()->usertype === 'admin' && $ulasan->id_user !== Auth::id())
                                <form action="{{ route('ulasan.destroy', $ulasan->id_ulasan) }}" method="POST"
                                    onsubmit="return confirm('Admin, yakin ingin menghapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mt-1">Hapus (Admin)</button>
                                </form>
                            @endif

                        </div>
                    @endforeach
                </section>
            </div>
        </section>

    </main>

    <x-footer />

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/php-email-form/validate.js"></script>
    <script src="/assets/vendor/aos/aos.js"></script>
    <script src="/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/vendor/purecounter/purecounter_vanilla.js"></script>

    <!-- Main JS File -->
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/layanan.js"></script>

</body>

</html>