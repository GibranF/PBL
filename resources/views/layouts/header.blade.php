<!-- resources/views/layouts/header.blade.php -->
<header id="header" class="header d-flex align-items-center fixed-top">
    <div
      class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
      <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">Island</h1>
      </a>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">Tentang Kami</a></li>
          <li><a href="#features">Info Layanan</a></li>
          {{-- <li class="dropdown">
            <a href="#"><span>Layanan</span><i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a class="#">Layanan Kami</a></li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
            </ul>
          </li> --}}
          <li><a href="#contact">Contact</a></li>
          <li><a href="#komentar">Ulasan & Rating</a></li>
          @guest
            <li><a href="{{ route('register') }}">Daftar</a></li>
            <li><a href="{{ route('login') }}">Masuk</a></li>
          @endguest
          @auth
            <li class="dropdown">
              <a href="#"><span>{{ Auth::user()->name }}</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                @if(Auth::user()->usertype === 'admin')
                  <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                @endif
                <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                      Logout
                    </a>
                  </form>
                </li>
              </ul>
            </li>
            @if(Auth::user()->usertype === 'customer')
              @php
                $belumDibayarCount = App\Models\Transaksi::where('id_user', Auth::id())
                  ->where('status_pembayaran', 'belum dibayar')
                  ->count();
              @endphp
              <li class="position-relative">
                <a href="{{ route('customer.pesanan.index') }}" class="cart-icon position-relative">
                  <i class="bi bi-cart" style="font-size: 1.4rem;"></i>
                  @if($belumDibayarCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size: 0.65rem;">
                      {{ $belumDibayarCount }}
                    </span>
                  @endif
                </a>
              </li>
            @endif
          @endauth
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
</header>
