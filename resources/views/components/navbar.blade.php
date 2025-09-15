<!-- resources/views/components/navbar.blade.php -->
<header id="header" class="header d-flex align-items-center fixed-top">
    <div
        class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="d-flex align-items-center">
            <div class="logo-circle">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Island" class="logo-img">
            </div>
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
                <li>
                    <a href="{{ url('/') }}#hero"
                        class="{{ request()->is('/') && request()->segment(1) == '' ? 'active' : '' }}">
                        Home
                    </a>
                </li>
                <li><a href="{{ url('/') }}#features">Info Layanan</a></li>
                <li><a href="{{ url('/') }}#contact">Contact</a></li>
                <li><a href="{{ url('/') }}#komentar">Ulasan & Rating</a></li>

                @guest
                    <li><a href="{{ route('register') }}">Daftar</a></li>
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                @endguest

                @auth
                    <li class="dropdown">
                        <a href="#">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            {{-- Admin Dashboard --}}
                            @if (Auth::user()->usertype === 'admin')
                                <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                            @endif

                            {{-- Owner Dashboard --}}
                            @if (Auth::user()->usertype === 'owner')
                                <li><a href="{{ route('owner.dashboard') }}">Dashboard Owner</a></li>
                            @endif

                            <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>

                    {{-- Cart hanya untuk customer --}}
                    @if (Auth::user()->usertype === 'customer' && !request()->is('keranjang'))
                        <li class="position-relative">
                            <a href="{{ route('customer.pesanan.index') }}" class="cart-icon position-relative">
                                <i class="bi bi-cart" style="font-size: 1.4rem;"></i>
                                @if (!empty($belumDibayarCount) && $belumDibayarCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
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
