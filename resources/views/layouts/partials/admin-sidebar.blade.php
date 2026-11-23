<aside class="sidebar" id="sidebar">
  <div>
    <div class="sidebar-header">
      <img src="{{ asset('images/logo.png') }}" alt="Logo cabil.rent" class="sidebar-logo" decoding="async">
      <div>
        <span class="sidebar-brand">CABIL.RENT</span>
        <span class="sidebar-sub">ADMIN PANEL</span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <p class="nav-section">UTAMA</p>
      <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
          <rect x="14" y="3" width="7" height="5" rx="1.5"></rect>
          <rect x="14" y="12" width="7" height="9" rx="1.5"></rect>
          <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
        </svg>
        <span>Dashboard</span>
      </a>
      <a href="{{ url('/pesanan') }}" class="nav-item {{ request()->is('pesanan*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2z"></path>
          <line x1="9" y1="8" x2="15" y2="8"></line>
          <line x1="9" y1="12" x2="15" y2="12"></line>
        </svg>
        <span>Pesanan</span>
      </a>

      <p class="nav-section">KATALOG</p>
      <a href="{{ url('/produk') }}" class="nav-item {{ request()->is('produk*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
          <polyline points="3.3 7 12 12 20.7 7"></polyline>
          <line x1="12" y1="22" x2="12" y2="12"></line>
        </svg>
        <span>Produk</span>
      </a>
      <a href="{{ url('/kategori') }}" class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="8" y1="6" x2="21" y2="6"></line>
          <line x1="8" y1="12" x2="21" y2="12"></line>
          <line x1="8" y1="18" x2="21" y2="18"></line>
          <line x1="3" y1="6" x2="3.01" y2="6"></line>
          <line x1="3" y1="12" x2="3.01" y2="12"></line>
          <line x1="3" y1="18" x2="3.01" y2="18"></line>
        </svg>
        <span>Kategori</span>
      </a>

      <p class="nav-section">PENGGUNA</p>
      <a href="{{ url('/pengguna') }}" class="nav-item {{ request()->is('pengguna*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span>Pengguna</span>
      </a>
    </nav>
  </div>

  <div class="sidebar-footer">
    <div class="user-info">
      <span class="avatar">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->username ?? 'A', 0, 2)) }}</span>
      <div class="user-detail">
        <span class="user-name">{{ Auth::user()->nama_lengkap ?? Auth::user()->username ?? 'Admin' }}</span>
        <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'admin') }}</span>
      </div>
    </div>
    <button class="btn-logout" data-open-logout title="Keluar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
        <polyline points="16 17 21 12 16 7"></polyline>
        <line x1="21" y1="12" x2="9" y2="12"></line>
      </svg>
    </button>
  </div>
</aside>
