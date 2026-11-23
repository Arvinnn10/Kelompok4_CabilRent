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

      <a href="{{ url('/keranjang') }}" class="nav-icon-btn" title="Keranjang">
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
        <a href="{{ route('logout') }}" class="nav-icon-btn" title="Keluar / Logout ({{ Auth::user()->nama_lengkap ?? Auth::user()->username }})" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="9"/>
            <circle cx="12" cy="10" r="3"/>
            <path d="M6.168 18.849a6 6 0 0 1 11.664 0"/>
          </svg>
        </a>
      @else
        <a href="{{ route('login') }}" class="nav-icon-btn" title="Masuk / Login">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="9"/>
            <circle cx="12" cy="10" r="3"/>
            <path d="M6.168 18.849a6 6 0 0 1 11.664 0"/>
          </svg>
        </a>
      @endif
    </div>
  </div>
</header>
