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
            <p class="header-desc">200 kebaya - 89 tersedia</p>
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
        <div class="search-bar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" class="search-input" placeholder="Cari nama kebaya" style="width: 280px;">
        </div>

        <div class="filter-tabs">
          <button class="filter-tab active">Semua</button>
          <button class="filter-tab">Akad</button>
          <button class="filter-tab">Wisuda</button>
          <button class="filter-tab">Pesta</button>
          <button class="filter-tab">Adat</button>
        </div>
      </div>

      <!-- Daftar kartu produk kebaya dan sepatu -->
      <div class="product-grid">
        <!-- Produk brokat maroon -->
        <div class="product-card">
          <!-- Foto produk, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="product-img-container">
            <img src="{{ asset('images/produk-brokat-maroon.png') }}" alt="Brokat Maroon" class="product-img">
          </div>
          <h4 class="product-title">Brokat Maroon</h4>
          <p class="product-category-size">Akad · S M L XL</p>
          <div class="product-footer">
            <div class="product-price">Rp 250.000<span>/hari</span></div>
            <span class="badge badge-available">Tersedia</span>
          </div>
        </div>

        <!-- Produk tulle sage green -->
        <div class="product-card">
          <!-- Foto produk, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="product-img-container">
            <img src="{{ asset('images/produk-tulle-sage.png') }}" alt="Tulle Sage Green" class="product-img">
          </div>
          <h4 class="product-title">Tulle Sage Green</h4>
          <p class="product-category-size">Wisuda · S M L</p>
          <div class="product-footer">
            <div class="product-price">Rp 200.000<span>/hari</span></div>
            <span class="badge badge-available">Tersedia</span>
          </div>
        </div>

        <!-- Produk encim gold klasik -->
        <div class="product-card">
          <!-- Foto produk, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="product-img-container">
            <img src="{{ asset('images/produk-encim-gold.png') }}" alt="Encim Gold Klasik" class="product-img">
          </div>
          <h4 class="product-title">Encim Gold Klasik</h4>
          <p class="product-category-size">Adat · M L XL</p>
          <div class="product-footer">
            <div class="product-price">Rp 280.000<span>/hari</span></div>
            <span class="badge badge-waiting">Disewa</span>
          </div>
        </div>

        <!-- Produk modern navy lace -->
        <div class="product-card">
          <!-- Foto produk, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="product-img-container">
            <img src="{{ asset('images/produk-modern-navy.png') }}" alt="Modern Navy Lace" class="product-img">
          </div>
          <h4 class="product-title">Modern Navy Lace</h4>
          <p class="product-category-size">Pesta · S M L</p>
          <div class="product-footer">
            <div class="product-price">Rp 240.000<span>/hari</span></div>
            <span class="badge badge-available">Tersedia</span>
          </div>
        </div>

        <!-- Produk putih gading renda -->
        <div class="product-card">
          <!-- Foto produk, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="product-img-container">
            <img src="{{ asset('images/produk-putih-gading.png') }}" alt="Putih Gading Renda" class="product-img">
          </div>
          <h4 class="product-title">Putih Gading Renda</h4>
          <p class="product-category-size">Akad · S M L</p>
          <div class="product-footer">
            <div class="product-price">Rp 260.000<span>/hari</span></div>
            <span class="badge badge-waiting">Disewa</span>
          </div>
        </div>

        <!-- Produk rose dusty satin -->
        <div class="product-card">
          <!-- Foto produk, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="product-img-container">
            <img src="{{ asset('images/produk-rose-dusty.png') }}" alt="Rose Dusty Satin" class="product-img">
          </div>
          <h4 class="product-title">Rose Dusty Satin</h4>
          <p class="product-category-size">Pesta · S M L XL</p>
          <div class="product-footer">
            <div class="product-price">Rp 230.000<span>/hari</span></div>
            <span class="badge badge-available">Tersedia</span>
          </div>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
