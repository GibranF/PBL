/**
* Template Name: iLanding
* Template URL: https://bootstrapmade.com/ilanding-bootstrap-landing-page-template/
* Updated: Nov 12 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";
  document.addEventListener('DOMContentLoaded', function () {
    const header = document.querySelector('.header');
    const containerFluid = document.querySelector('.container-fluid');
    const navLinks = document.querySelectorAll('.navmenu a[href^="#"]');
    const layananContainer = document.getElementById('layanan-container');
    const addLayananButton = document.getElementById('add-layanan');
    const totalInput = document.getElementById('total');

    // Pastikan semua elemen ada sebelum melanjutkan
    if (!layananContainer || !addLayananButton || !totalInput || !header || !containerFluid) {
        console.error('Salah satu elemen kunci tidak ditemukan:', {
            layananContainer,
            addLayananButton,
            totalInput,
            header,
            containerFluid
        });
        return;
    }

    // Fungsi untuk mengatur padding-top berdasarkan tinggi header
    function setPaddingTop() {
        if (header && containerFluid) {
            const headerHeight = header.offsetHeight;
            containerFluid.style.paddingTop = `${headerHeight + 10}px`; // Tambah 10px untuk ruang ekstra
            console.log('Tinggi header:', headerHeight, 'px');
        }
    }

    // Jalankan saat halaman dimuat dan saat window di-resize
    setPaddingTop();
    window.addEventListener('resize', setPaddingTop);

    // Efek scroll untuk header
    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Tangani klik pada tautan navbar
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                const headerHeight = header.offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - headerHeight - 10; // Tambah offset ekstra

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Fungsi untuk mengatur event listener pada layanan
    function setupLayananEvents(container) {
        const layananSelect = container.querySelector('.layanan');
        const hargaInput = container.querySelector('.harga');
        const hargaDisplay = container.querySelector('.harga-display');

        if (layananSelect && hargaInput && hargaDisplay) {
            layananSelect.addEventListener('change', function () {
                console.log('Layanan dipilih:', this.value);
                const selectedOption = this.options[this.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                console.log('Data-price yang dibaca:', selectedOption.getAttribute('data-price'));
                console.log('Harga yang akan ditetapkan:', price);
                // Simpan nilai numerik murni di input hidden
                hargaInput.value = price;
                // Tampilkan format mata uang di input display
                hargaDisplay.value = price.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).replace('Rp', '').trim() || 0;
                console.log('Harga setelah diupdate (hidden):', hargaInput.value);
                console.log('Harga setelah diupdate (display):', hargaDisplay.value);
                hitungTotal();
            });
        } else {
            console.error('Elemen layanan, harga, atau harga-display tidak ditemukan di container:', container);
        }
    }

    // Event untuk menambahkan layanan baru
    addLayananButton.addEventListener('click', function () {
        const index = layananContainer.querySelectorAll('.layanan-box').length;
        const newLayananGroup = document.createElement('div');
        newLayananGroup.classList.add('layanan-box', 'col-md-6', 'mb-3');
        newLayananGroup.innerHTML = `
            <div class="input-box">
                <label for="layanan">Pilih Layanan</label>
                <select name="layanan[${index}][id_layanan]" class="form-control layanan input-box">
                    <option value="">Pilih Layanan</option>
                    @foreach ($layanan as $service)
                        <option value="{{ $service->id_layanan }}" data-price="{{ $service->harga }}">{{ $service->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-box">
                <label for="harga">Harga</label>
                <input type="text" class="form-control harga-display input-box" readonly />
                <input type="hidden" name="layanan[${index}][harga]" class="harga" />
            </div>
            <div class="input-box">
                <label>Kg/Meter persegi/Satuan</label>
                <input type="number" name="layanan[${index}][dimensi]" class="form-control dimensi input-box" step="0.01" required />
                <small class="text-muted">Gunakan titik (.)</small>
            </div>
            <div class="input-box">
                <label for="satuan">Satuan</label>
                <select name="layanan[${index}][satuan]" class="form-control satuan input-box">
                    <option value="kg">Kilogram</option>
                    <option value="m2">Meter persegi</option>
                    <option value="pcs">Pcs</option>
                </select>
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-2 remove-layanan">Hapus Layanan</button>
        `;
        layananContainer.appendChild(newLayananGroup);
        setupLayananEvents(newLayananGroup);
    });

    // Event untuk menghapus layanan
    layananContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-layanan')) {
            e.target.closest('.layanan-box').remove();
            hitungTotal();
        }
    });

    // Event untuk mengupdate total saat dimensi diubah
    layananContainer.addEventListener('input', function (e) {
        if (e.target.classList.contains('dimensi')) {
            hitungTotal();
        }
    });

    // Event untuk mengupdate total saat satuan diubah
    layananContainer.addEventListener('change', function (e) {
        if (e.target.classList.contains('satuan')) {
            hitungTotal();
        }
    });

    // Event untuk mengupdate total saat jarak atau antar-jemput diubah
    const jarakInput = document.querySelector('[name="jarak_km"]');
    const antarJemputSelect = document.querySelector('[name="antar_jemput"]');
    if (jarakInput) jarakInput.addEventListener('input', hitungTotal);
    if (antarJemputSelect) antarJemputSelect.addEventListener('change', hitungTotal);

    // Fungsi untuk menghitung total
    function hitungTotal() {
        let total = 0;
        const layananGroups = document.querySelectorAll('.layanan-box');

        layananGroups.forEach(function (group) {
            const hargaInput = group.querySelector('.harga');
            const dimensiInput = group.querySelector('.dimensi');
            if (hargaInput && dimensiInput) {
                const harga = parseFloat(hargaInput.value) || 0;
                const dimensi = parseFloat(dimensiInput.value) || 0;
                const subtotal = harga * dimensi;
                total += subtotal;
                console.log(`Subtotal: ${harga} * ${dimensi} = ${subtotal}`);
            } else {
                console.error('Elemen harga atau dimensi tidak ditemukan dalam group:', group);
            }
        });

        let biayaAntar = 0;
        if (antarJemputSelect && jarakInput) {
            const antarJemput = antarJemputSelect.value;
            const jarak = parseFloat(jarakInput.value) || 0;
            biayaAntar = antarJemput === 'yes' ? (jarak <= 3 ? 0 : (jarak - 3) * 5000) : 0;
            console.log(`Jarak: ${jarak} km, Biaya Antar: ${biayaAntar}`);
        }

        if (totalInput) {
            const totalBiaya = total + biayaAntar;
            totalInput.value = totalBiaya.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).replace('Rp', '').trim() || 0;
            console.log(`Total Biaya: ${totalInput.value}`);
        } else {
            console.error('Elemen total tidak ditemukan');
        }
    }

    // Tambahkan event listener untuk layanan awal
    const initialLayananGroups = layananContainer.querySelectorAll('.layanan-box');
    initialLayananGroups.forEach(setupLayananEvents);

    // Jalankan hitungTotal saat halaman dimuat
    hitungTotal();
});

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.style.color = 'white';
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Frequently Asked Questions Toggle
   */
  document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle').forEach((faqItem) => {
    faqItem.addEventListener('click', () => {
      faqItem.parentNode.classList.toggle('faq-active');
    });
  });

  /**
   * Correct scrolling position upon page load for URLs containing hash links.
   */
  window.addEventListener('load', function(e) {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        setTimeout(() => {
          let section = document.querySelector(window.location.hash);
          let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: 'smooth'
          });
        }, 100);
      }
    }
  });

  /**
   * Navmenu Scrollspy
   */
  let navmenulinks = document.querySelectorAll('.navmenu a');

  function navmenuScrollspy() {
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);

})();



