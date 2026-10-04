<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk - cabil.rent Admin Panel</title>
  
  <!-- CSS Khusus Halaman Produk  -->
  <link rel="stylesheet" href="{{ asset('css/produk.css') }}">
  
  <!-- Font Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

  <div class="app-container">
    <!-- Sidebar navigasi -->
    <aside class="sidebar">
      <div>
        <div class="sidebar-header">
          <span class="sidebar-brand">cabil.rent</span>
          <span class="sidebar-subtitle">Admin Panel</span>
        </div>

        <nav class="sidebar-nav">
          <!-- Menu dashboard -->
          <a href="{{ url('/dashboard') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
            <span>Dashboard</span>
          </a>

          <!-- Menu kategori -->
          <a href="{{ url('/kategori') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Kategori</span>
          </a>

          <!-- Menu produk -->
          <a href="{{ url('/produk') }}" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
            </svg>
            <span>Produk</span>
          </a>

          <!-- Menu pesanan -->
          <a href="{{ url('/pesanan') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="5" width="20" height="14" rx="2"></rect>
              <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            <span>Pesanan</span>
          </a>
        </nav>
      </div>

      <!-- Profil admin di bagian bawah sidebar -->
      <div class="sidebar-footer">
        <a href="{{ route('logout') }}" class="user-profile" title="Klik untuk Logout">
          <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? (Auth::user()->username ?? 'Admin'), 0, 1)) }}</div>
          <div class="user-info">
            <span class="user-name">{{ Auth::user()->nama_lengkap ?? (Auth::user()->username ?? 'Admin') }}</span>
            <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
          </div>
        </a>
      </div>
    </aside>

    <!-- Konten utama produk -->
    <main class="main-wrapper">
      <!-- Bagian atas judul halaman dan tombol -->
      <div class="top-header">
        <div class="header-title-box">
          <svg class="header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
          </svg>
          <div>
            <h1 class="header-title">Produk</h1>
            <p class="header-desc">{{ $totalProduk }} produk - {{ $tersediaCount }} tersedia</p>
          </div>
        </div>

        <div class="header-actions">
          <button class="btn-primary">
            <span>+</span> Tambah Produk
          </button>
        </div>
      </div>

      <!-- Kolom cari dan tombol filter kategori -->
      <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;">
        <form action="{{ route('produk.index') }}" method="GET" class="search-bar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" name="search" class="search-input" placeholder="Cari nama kebaya" style="width: 280px;" value="{{ request('search') }}">
        </form>

        <div class="filter-tabs">
          <a href="{{ route('produk.index') }}" class="filter-tab {{ !request('kategori') ? 'active' : '' }}">Semua</a>
          @foreach($categories as $cat)
          <a href="{{ route('produk.index', ['kategori' => $cat->id]) }}" class="filter-tab {{ request('kategori') == $cat->id ? 'active' : '' }}">{{ $cat->nama_kategori }}</a>
          @endforeach
        </div>
      </div>

      <!-- Daftar kartu produk kebaya dan sepatu -->
      <div class="product-grid">
        @forelse($products as $produk)
        <div class="product-card">
          <div class="product-img-container">
            @php
              $img = $produk->foto_produk;
              $imgUrl = (str_starts_with($img, 'assets/') || str_starts_with($img, 'images/')) 
                ? asset($img) 
                : asset('images/' . $img);
            @endphp
            <img src="{{ $imgUrl }}" alt="{{ $produk->nama_produk }}" class="product-img">
          </div>
          <h4 class="product-title">{{ $produk->nama_produk }}</h4>
          <p class="product-category-size">{{ $produk->category->nama_kategori ?? '-' }}</p>
          <div class="product-footer">
            <div class="product-price">Rp {{ number_format($produk->harga_sewa, 0, ',', '.') }}<span>/hari</span></div>
            @if($produk->status_produk == 'tersedia')
              <span class="badge badge-available">Tersedia</span>
            @else
              <span class="badge badge-waiting">Disewa</span>
            @endif
          </div>
        </div>
        @empty
        <p style="padding: 20px; color: #888;">Belum ada produk yang ditambahkan.</p>
        @endforelse
      </div>
    </main>
  </div>

</body>
</html>
