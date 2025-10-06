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
    slides.forEach((s) => s.classList.remove("active"));
    dots.forEach((d) => d.classList.remove("active"));

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

  // ---- Swipe / Drag Feature ----
  let startX = 0;
  let isDragging = false;

  // Mouse events
  slideContainer.addEventListener("mousedown", (e) => {
    isDragging = true;
    startX = e.pageX;
  });

  slideContainer.addEventListener("mousemove", (e) => {
    if (!isDragging) return;
    let diff = e.pageX - startX;
    if (diff > 50) { // geser ke kanan
      prevSlide();
      isDragging = false;
    } else if (diff < -50) { // geser ke kiri
      nextSlide();
      isDragging = false;
    }
  });

  slideContainer.addEventListener("mouseup", () => {
    isDragging = false;
  });
  slideContainer.addEventListener("mouseleave", () => {
    isDragging = false;
  });

  // Touch events (mobile)
  slideContainer.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
  });

  slideContainer.addEventListener("touchmove", (e) => {
    let diff = e.touches[0].clientX - startX;
    if (diff > 50) {
      prevSlide();
      startX = e.touches[0].clientX; // reset supaya swipe bisa berulang
    } else if (diff < -50) {
      nextSlide();
      startX = e.touches[0].clientX;
    }
  });

  function prevSlide() {
    let prev = (current - 1 + total) % total;
    showSlide(prev);
    resetAutoSlide();
  }

  // ---- Inisialisasi ----
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
                            <!--  Gambar statis -->
                              @php
                $gambar = match($layanan->nama_layanan) {
                    'Cuci Kering' => 'cucikering.png',
                    'Setrika' => 'setrika.png',
                    'Cuci Lipat' => 'cucilipat.png',
                    'Testing' => 'cucilipat.png',
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

<!-- Proses Kerja Section -->
<section id="proses-kerja" class="call-to-action section">
    <div class="row justify-content-center">
        <div class="col-lg-10 text-center">
            <div class="cta-card p-5 rounded-4 position-relative overflow-hidden">
                <div class="cta-content position-relative z-index-2">
                    <h2 class="display-5 mb-3 fw-bold text-white">Bagaimana Istana Laundry Bekerja</h2>
                    <p class="mb-4 fs-5">Nikmati layanan cepat, profesional, dan nyaman dalam beberapa langkah mudah!</p>

                    <div class="row text-start mb-4">
                        <div class="col-md-3 mb-3">
                            <h5 class="fw-bold text-white"><i class="bi bi-bag-fill me-2"></i>1. Masukkan Pakaian</h5>
                            <p class="text-white">Serahkan pakaian kotor Anda ke kami, baik secara langsung atau lewat layanan antar jemput.</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="fw-bold text-white"><i class="bi bi-brush-fill me-2"></i>2. Proses Pencucian</h5>
                            <p class="text-white">Pakaian dicuci dengan deterjen ramah lingkungan dan staf profesional untuk hasil bersih maksimal.</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="fw-bold text-white"><i class="bi bi-clock-fill me-2"></i>3. Setrika & Periksa</h5>
                            <p class="text-white">Setiap pakaian diperiksa dan disetrika agar rapi dan siap pakai.</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="fw-bold text-white"><i class="bi bi-truck me-2"></i>4. Pengantaran / Ambil</h5>
                            <p class="text-white">Pakaian siap diambil atau kami antar kembali ke alamat Anda dengan aman dan tepat waktu.</p>
                        </div>
                    </div>

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


<!-- FAQ Section -->
<section id="faq" class="faq section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="section-title text-center mb-5">
            <h2>FAQ Istana Laundry</h2>
            <p>Pertanyaan yang sering diajukan oleh pelanggan</p>
        </div>

        <div class="row">
            <!-- Kiri: FAQ 1-5 -->
            <div class="col-lg-6">
                <div class="accordion" id="faqAccordionLeft">
                    <!-- FAQ 1 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="false" aria-controls="faqCollapse1">
                                1. Apa saja layanan yang disediakan Istana Laundry?
                            </button>
                        </h2>
                        <div id="faqCollapse1" class="accordion-collapse collapse" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordionLeft">
                            <div class="accordion-body">
                                Kami menyediakan layanan cuci pakaian harian, cuci sepatu, setrika, dan layanan kiloan.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                2. Berapa lama proses pencucian pakaian?
                            </button>
                        </h2>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordionLeft">
                            <div class="accordion-body">
                                Pakaian biasanya selesai dalam 24 jam, tergantung jenis layanan dan jumlah pakaian.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                3. Apakah Istana Laundry menerima layanan antar-jemput?
                            </button>
                        </h2>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordionLeft">
                            <div class="accordion-body">
                                Ya, kami menyediakan layanan antar-jemput untuk area tertentu. Silakan hubungi kami untuk detailnya.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                4. Bagaimana cara melakukan pembayaran?
                            </button>
                        </h2>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordionLeft">
                            <div class="accordion-body">
                                Pembayaran dapat dilakukan secara tunai di tempat atau melalui transfer bank/e-wallet.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                                5. Apakah pakaian saya aman di Istana Laundry?
                            </button>
                        </h2>
                        <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordionLeft">
                            <div class="accordion-body">
                                Kami menjaga keamanan pakaian Anda dengan sistem penomoran dan staf profesional.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanan: FAQ 6-10 -->
            <div class="col-lg-6">
                <div class="accordion" id="faqAccordionRight">
                    <!-- FAQ 6 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading6">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                                6. Apakah Istana Laundry menggunakan deterjen ramah lingkungan?
                            </button>
                        </h2>
                        <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6" data-bs-parent="#faqAccordionRight">
                            <div class="accordion-body">
                                Ya, kami menggunakan deterjen dan produk ramah lingkungan yang aman untuk pakaian dan kulit.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 7 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading7">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
                                7. Bagaimana jika pakaian hilang atau rusak?
                            </button>
                        </h2>
                        <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7" data-bs-parent="#faqAccordionRight">
                            <div class="accordion-body">
                                Kami bertanggung jawab penuh dan menyediakan kompensasi sesuai kebijakan jika terjadi kehilangan atau kerusakan.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 8 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading8">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse8" aria-expanded="false" aria-controls="faqCollapse8">
                                8. Apakah ada promo atau diskon untuk pelanggan tetap?
                            </button>
                        </h2>
                        <div id="faqCollapse8" class="accordion-collapse collapse" aria-labelledby="faqHeading8" data-bs-parent="#faqAccordionRight">
                            <div class="accordion-body">
                                Ya, kami memiliki promo khusus untuk pelanggan tetap dan musiman. Silakan cek media sosial kami untuk info terbaru.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 9 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading9">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse9" aria-expanded="false" aria-controls="faqCollapse9">
                                9. Apakah bisa mencuci pakaian dalam jumlah besar?
                            </button>
                        </h2>
                        <div id="faqCollapse9" class="accordion-collapse collapse" aria-labelledby="faqHeading9" data-bs-parent="#faqAccordionRight">
                            <div class="accordion-body">
                                Bisa, kami melayani cuci kiloan dan pesanan dalam jumlah besar sesuai kebutuhan pelanggan.
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 10 -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading10">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse10" aria-expanded="false" aria-controls="faqCollapse10">
                                10. Bagaimana cara menghubungi Istana Laundry?
                            </button>
                        </h2>
                        <div id="faqCollapse10" class="accordion-collapse collapse" aria-labelledby="faqHeading10" data-bs-parent="#faqAccordionRight">
                            <div class="accordion-body">
                                Anda bisa menghubungi kami melalui telepon, WhatsApp, atau kunjungi langsung cabang terdekat.
                            </div>
                        </div>
                    </div>
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
    <div class="container" style="max-width: 800px;">
        
        <!-- Judul -->
        <h3 class="text-center" style="color: #800080;">Rating dan Ulasan</h3>
        <p class="text-center mb-4">Tulis komentar dan rating Anda</p>

        <!-- Form Ulasan Baru -->
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
                <button type="submit" class="btn btn-primary rounded">Kirim</button>
            </div>
        </form>
@php
    // Ambil semua ulasan
    $allUlasan = \App\Models\Ulasan::all();
    $totalUlasan = $allUlasan->count();
    $totalRating = $allUlasan->sum('rating');
    $rataRata = $totalUlasan > 0 ? round($totalRating / $totalUlasan, 1) : 0;
@endphp

<!-- Rata-rata Rating -->
<div class="mb-4 text-center">
    <h4 class="mb-0">Rating Keseluruhan</h4>
    <div style="font-size: 22px; color: gold;">
        {{ str_repeat('★', floor($rataRata)) }}{{ str_repeat('☆', 5 - floor($rataRata)) }}
        <span class="text-muted" style="font-size: 18px;">{{ $rataRata }}/5</span>
    </div>
    <small class="text-muted">Berdasarkan {{ $totalUlasan }} ulasan</small>
</div>

       <!-- Filter Ulasan -->
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-4">Ulasan Terbaru</h4>  <!-- tambah mb-0 -->
    <form method="GET" action="#komentar" class="d-flex">
        <select name="filter_rating" class="form-select" onchange="this.form.submit()" style="margin-left: 15px;">  <!-- tambah margin-left -->
            <option value="">Rating</option>
            <option value="5" {{ request('filter_rating') == 5 ? 'selected' : '' }}>★★★★★</option>
            <option value="4" {{ request('filter_rating') == 4 ? 'selected' : '' }}>★★★★</option>
            <option value="3" {{ request('filter_rating') == 3 ? 'selected' : '' }}>★★★</option>
            <option value="2" {{ request('filter_rating') == 2 ? 'selected' : '' }}>★★</option>
            <option value="1" {{ request('filter_rating') == 1 ? 'selected' : '' }}>★</option>
        </select>
    </form>
</div>

        <!-- Query Ulasan -->
        @php
            $query = \App\Models\Ulasan::latest();
            if (request('filter_rating')) {
                $query->where('rating', request('filter_rating'));
            }
            $ulasans = $query->paginate(5); // tampil 5 per halaman
        @endphp

        <!-- Daftar Ulasan -->
        @foreach ($ulasans as $ulasan)
            <div class="border p-3 mb-3 rounded shadow-sm">
                
                <!-- Header Ulasan -->
<div class="d-flex justify-content-between align-items-start">
    <div class="d-flex align-items-center">
        <!-- Foto Profil (trigger modal) -->
        <img src="{{ $ulasan->user->profile_photo ? asset('storage/' . $ulasan->user->profile_photo) : asset('images/default-foto.png') }}"
             alt="Foto {{ $ulasan->user->name }}"
             class="rounded-circle me-2"
             style="width:40px; height:40px; object-fit:cover; cursor:pointer;"
             data-bs-toggle="modal" data-bs-target="#fotoModal{{ $ulasan->id }}">

        <!-- Nama + Tanggal -->
        <div>
            <strong>{{ $ulasan->user->name ?? 'User tidak ditemukan' }}</strong>
            <small class="text-muted d-block">{{ $ulasan->created_at->diffForHumans() }}</small>
        </div>
    </div>

    <!-- Rating -->
    <div>
        <span style="color: gold; font-size: 18px;">
            {{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}
        </span>
    </div>
</div>

<!-- Modal Foto Profil -->
<div class="modal fade" id="fotoModal{{ $ulasan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Tambah modal-lg -->
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body text-center">
                <img src="{{ $ulasan->user->profile_photo ? asset('storage/' . $ulasan->user->profile_photo) : asset('images/default-foto.png') }}"
                     alt="Foto {{ $ulasan->user->name }}"
                     class="img-fluid rounded"
                     style="max-height: 90vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>



                <!-- Isi Ulasan -->
                <p class="mt-2 mb-1">{{ $ulasan->pesan }}</p>

                <!-- Tombol untuk Pemilik -->
                @if (Auth::check() && $ulasan->id_user == Auth::id())
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <!-- Edit -->
                        <button class="btn btn-sm btn-warning px-3"
                                data-bs-toggle="collapse"
                                data-bs-target="#editUlasan{{ $ulasan->id_ulasan }}">
                            ✏️ Edit
                        </button>

                        <!-- Hapus -->
                        <form action="{{ route('ulasan.destroy', $ulasan->id_ulasan) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger px-3">🗑️ Hapus</button>
                        </form>
                    </div>

                    <!-- Form Edit Collapse -->
                    <div class="collapse mt-2" id="editUlasan{{ $ulasan->id_ulasan }}">
                        <form action="{{ route('ulasan.update', $ulasan->id_ulasan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <textarea name="komentar" class="form-control mb-2" required>{{ $ulasan->pesan }}</textarea>
                            <select name="rating" class="form-select mb-2" required>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ $ulasan->rating == $i ? 'selected' : '' }}>
                                        {{ str_repeat('★', $i) }}
                                    </option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-success btn-sm">💾 Simpan</button>
                        </form>
                    </div>
                @endif

                <!-- Tombol Hapus Admin -->
                @if (Auth::check() && Auth::user()->usertype === 'admin' && $ulasan->id_user !== Auth::id())
                    <form action="{{ route('admin.ulasan.destroy', $ulasan->id_ulasan) }}"
                          method="POST"
                          onsubmit="return confirm('Admin, yakin ingin menghapus ulasan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm mt-1">Hapus (Admin)</button>
                    </form>
                @endif
            </div>
        @endforeach

        <!-- Pagination Bootstrap 5 - Diperbaiki -->
        <div class="mt-4 d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <!-- Previous Page Link -->
                    @if ($ulasans->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">&laquo; Sebelumnya</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $ulasans->previousPageUrl() }}#komentar" rel="prev">&laquo; Sebelumnya</a>
                        </li>
                    @endif

                    <!-- Pagination Elements -->
                    @foreach ($ulasans->getUrlRange(1, $ulasans->lastPage()) as $page => $url)
                        @if ($page == $ulasans->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}#komentar">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    <!-- Next Page Link -->
                    @if ($ulasans->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $ulasans->nextPageUrl() }}#komentar" rel="next">Selanjutnya &raquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Selanjutnya &raquo;</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</section>

<!-- CSS tambahan untuk styling yang lebih baik -->
<style>
/* Pastikan pagination terlihat dan dapat diklik */
.pagination {
    margin: 20px 0;
}

.page-link {
    color: #800080;
    border: 1px solid #dee2e6;
    padding: 8px 16px;
    text-decoration: none;
}

.page-link:hover {
    color: #5a005a;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: #800080;
    border-color: #800080;
    color: white;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Hilangkan panah carousel */
.carousel-control-prev,
.carousel-control-next,
.swiper-button-next,
.swiper-button-prev {
    display: none !important;
}

/* Tambahan styling untuk form dan ulasan */
.form-control:focus, .form-select:focus {
    border-color: #800080;
    box-shadow: 0 0 0 0.2rem rgba(128, 0, 128, 0.25);
}

.btn-primary {
    background-color: #800080;
    border-color: #800080;
}

.btn-primary:hover {
    background-color: #5a005a;
    border-color: #5a005a;
}
</style>



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