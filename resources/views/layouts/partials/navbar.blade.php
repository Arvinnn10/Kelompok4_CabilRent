<header class="navbar">
  <div class="container nav-container">
    <a href="{{ url('/') }}" class="nav-brand">
      <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img" loading="lazy" decoding="async">
      <span>CABIL.RENT</span>
    </a>

    <ul class="nav-menu">
      <li><a href="{{ url('/') }}" class="nav-link {{ request()->is('/') || request()->is('home') ? 'active' : '' }}">Beranda</a></li>
      <li><a href="{{ url('/koleksi') }}" class="nav-link {{ request()->is('koleksi*') ? 'active' : '' }}">Koleksi</a></li>
      <li><a href="{{ url('/tentang') }}" class="nav-link {{ request()->is('tentang*') ? 'active' : '' }}">Tentang</a></li>
      <li><a href="{{ url('/kontak') }}" class="nav-link {{ request()->is('kontak*') ? 'active' : '' }}">Kontak</a></li>
    </ul>

    <div class="nav-actions">
      <form class="nav-search" action="{{ url('/koleksi') }}" method="GET">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari koleksi">
      </form>

      <a href="{{ url('/keranjang') }}" class="nav-icon-btn" title="Keranjang Belanja">
        <span class="cart-badge" id="cart-badge" hidden></span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M4 10h16l-2 10H6L4 10z"/>
          <path d="M4 10l5-6"/>
          <path d="M20 10l-5-6"/>
          <line x1="9" y1="14" x2="9" y2="17"/>
          <line x1="12" y1="14" x2="12" y2="17"/>
          <line x1="15" y1="14" x2="15" y2="17"/>
        </svg>
      </a>

      @if (Auth::check())
        <!-- Penanda User Sedang Login -->
        <div class="nav-auth-container is-logged-in">
          @if (Auth::user()->isAdmin())
            <a href="{{ url('/dashboard') }}" class="nav-user-chip" title="Klik untuk membuka Panel Admin">
              <span class="status-dot online" title="Status: Sedang Login"></span>
              <span class="nav-user-name">Admin: {{ Str::limit(Auth::user()->nama_lengkap ?? Auth::user()->username, 12) }}</span>
            </a>
          @else
            <a href="{{ route('riwayat.pesanan') }}" class="nav-user-chip" title="Klik untuk melihat Riwayat Pesanan">
              <span class="status-dot online" title="Status: Sedang Login"></span>
              <span class="nav-user-name">Halo, {{ Str::limit(Auth::user()->nama_lengkap ?? Auth::user()->username, 14) }}</span>
            </a>
          @endif

          <a href="{{ route('logout') }}" class="nav-btn-logout" title="Keluar / Logout ({{ Auth::user()->nama_lengkap ?? Auth::user()->username }})" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
          </a>
        </div>
      @else
        <!-- Penanda User Belum Login -->
        <div class="nav-auth-container is-guest">
          <span class="nav-guest-status" title="Status: Anda belum login">
            <span class="status-dot offline"></span>
            <span>Belum Login</span>
          </span>
          <a href="{{ route('login') }}" class="btn-nav-auth-login">Masuk</a>
          <a href="{{ route('register') }}" class="btn-nav-auth-register">Daftar</a>
        </div>
      @endif
    </div>
  </div>
</header>
