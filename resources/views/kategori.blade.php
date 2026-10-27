<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kategori - Cabil.Rent</title>
  
  <!-- CSS Khusus Halaman Kategori  -->
  <link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
  
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
          <img src="{{ asset('images/logo.png') }}" alt="Logo cabil.rent" class="sidebar-logo">
          <span class="sidebar-brand">CABIL.RENT</span>
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
          <a href="{{ url('/kategori') }}" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Kategori</span>
          </a>

          <!-- Menu produk -->
          <a href="{{ url('/produk') }}" class="nav-item">
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

          <!-- Menu keranjang -->
          <a href="{{ url('/keranjang') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Keranjang</span>
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

    <!-- Konten utama kategori -->
    <main class="main-wrapper">
      <!-- Bagian atas judul halaman dan tombol -->
      <div class="top-header">
        <div class="header-title-box">
          <svg class="header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          <div>
            <h1 class="header-title">Kategori</h1>
            <p class="header-desc">Kelola kelompok kategori yang disewakan</p>
          </div>
        </div>

        <div class="header-actions">
          <button class="btn-primary">
            <span>+</span> Tambah Kategori Baru
          </button>
        </div>
      </div>

      <!-- Kartu pilihan kategori produk -->
      <div class="category-cards-grid">
        <!-- Kartu kategori paket kebaya -->
        <div class="category-card">
          <!-- Foto kategori, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="category-img-container">
            <img src="{{ asset('images/kategori-kebaya.png') }}" alt="Paket Kebaya" class="category-img">
          </div>
          <h3 class="category-title">Paket Kebaya</h3>
          <p class="category-count">78 produk - 21 disewa</p>
          <div class="category-btn-row">
            <button class="btn-tan">Edit</button>
            <a href="{{ url('/produk') }}" class="btn-soft-blue">Lihat produk</a>
          </div>
        </div>

        <!-- Kartu kategori heels -->
        <div class="category-card">
          <!-- Foto kategori, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="category-img-container">
            <img src="{{ asset('images/kategori-heels.png') }}" alt="Heels" class="category-img">
          </div>
          <h3 class="category-title">Heels</h3>
          <p class="category-count">78 produk - 21 disewa</p>
          <div class="category-btn-row">
            <button class="btn-tan">Edit</button>
            <a href="{{ url('/produk') }}" class="btn-soft-blue">Lihat produk</a>
          </div>
        </div>

        <!-- Kartu kategori kemben -->
        <div class="category-card">
          <!-- Foto kategori, ganti atribut src jika ingin memakai foto sendiri -->
          <div class="category-img-container">
            <img src="{{ asset('images/kategori-kemben.png') }}" alt="Kemben" class="category-img">
          </div>
          <h3 class="category-title">Kemben</h3>
          <p class="category-count">78 produk - 21 disewa</p>
          <div class="category-btn-row">
            <button class="btn-tan">Edit</button>
            <a href="{{ url('/produk') }}" class="btn-soft-blue">Lihat produk</a>
          </div>
        </div>
      </div>

      <!-- Tabel daftar kategori dan status aktif -->
      <div class="table-card">
        <div class="card-panel-header">
          <h3 class="card-panel-title">Daftar Kategori</h3>
        </div>

        <div class="table-responsive">
          <table class="custom-table">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Produk</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="font-bold">Paket Kebaya</td>
                <td>Kebaya putih & pastel untuk ijab kabul</td>
                <td>30</td>
                <td>
                  <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                  </label>
                  <span style="font-size: 12px; margin-left: 8px; font-weight: 600;">Aktif</span>
                </td>
                <td>
                  <a href="#" class="action-link">Edit</a>
                  <a href="#" class="action-danger">Hapus</a>
                </td>
              </tr>
              <tr>
                <td class="font-bold">Heels</td>
                <td>Berbagai heels untuk acara</td>
                <td>45</td>
                <td>
                  <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                  </label>
                  <span style="font-size: 12px; margin-left: 8px; font-weight: 600;">Aktif</span>
                </td>
                <td>
                  <a href="#" class="action-link">Edit</a>
                  <a href="#" class="action-danger">Hapus</a>
                </td>
              </tr>
              <tr>
                <td class="font-bold">Kemben</td>
                <td>Brokat & payet untuk resepsi dan gala</td>
                <td>25</td>
                <td>
                  <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                  </label>
                  <span style="font-size: 12px; margin-left: 8px; font-weight: 600;">Aktif</span>
                </td>
                <td>
                  <a href="#" class="action-link">Edit</a>
                  <a href="#" class="action-danger">Hapus</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
