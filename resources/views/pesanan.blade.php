<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan - cabil.rent Admin Panel</title>
  
  <!-- CSS Khusus Halaman Pesanan  -->
  <link rel="stylesheet" href="{{ asset('css/pesanan.css') }}">
  
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
          <a href="{{ url('/produk') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
            </svg>
            <span>Produk</span>
          </a>

          <!-- Menu pesanan -->
          <a href="{{ url('/pesanan') }}" class="nav-item active">
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

    <!-- Konten utama pesanan -->
    <main class="main-wrapper">
      <!-- Bagian atas judul halaman dan tombol -->
      <div class="top-header">
        <div class="header-title-box">
          <svg class="header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
            <line x1="2" y1="10" x2="22" y2="10"></line>
          </svg>
          <div>
            <h1 class="header-title">Pesanan</h1>
            <p class="header-desc">200 kebaya - 89 tersedia</p>
          </div>
        </div>

        <div class="header-actions">
          <button class="btn-primary">
            <span>+</span> Pesanan Baru
          </button>
        </div>
      </div>

      <!-- Filter status pesanan dan kolom pencarian -->
      <div class="pesanan-filter-bar">
        <!-- Pilihan filter kategori -->
        <div class="filter-tabs">
          <button class="filter-tab active">Semua</button>
          <button class="filter-tab">Tertunda</button>
          <button class="filter-tab">Dikonfirmasi</button>
          <button class="filter-tab">Selesai</button>
          <button class="filter-tab">Dibatalkan</button>
        </div>

        <!-- Kolom pencarian -->
        <div class="search-bar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" class="search-input" placeholder="Cari id atau nama pelanggan" style="width: 260px;">
        </div>
      </div>

      <!-- Tabel rincian data pesanan -->
      <div class="table-card">
        <div class="table-responsive">
          <table class="custom-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>No Whatsapp</th>
                <th>Domisili</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>123</td>
                <td>Siti Rahmawati</td>
                <td>Brokat Maroon (M)</td>
                <td>0821-0222</td>
                <td class="font-bold">Banjarbaru</td>
                <td class="font-bold">Rp 750.000</td>
                <td><span class="badge badge-outline">Tertunda</span></td>
                <td><a href="#" class="action-link">Detail &#9656;</a></td>
              </tr>
              <tr>
                <td>123</td>
                <td>Dewi Lestari</td>
                <td>Tulle Sage Green (S)</td>
                <td>0821-3030</td>
                <td class="font-bold">Martapura</td>
                <td class="font-bold">Rp 400.000</td>
                <td><span class="badge badge-outline">Selesai</span></td>
                <td><a href="#" class="action-link">Detail &#9656;</a></td>
              </tr>
              <tr>
                <td>123</td>
                <td>Ayu Puspita</td>
                <td>Encim Gold Klasik (L)</td>
                <td>0821-3434</td>
                <td class="font-bold">Martapura</td>
                <td class="font-bold">Rp 560.000</td>
                <td><span class="badge badge-outline">Dikonfirmasi</span></td>
                <td><a href="#" class="action-link">Detail &#9656;</a></td>
              </tr>
              <tr>
                <td>123</td>
                <td>Maya Sari</td>
                <td>Modern Navy Lace (M)</td>
                <td>0821-6767</td>
                <td class="font-bold">Banjarmasin</td>
                <td class="font-bold">Rp 480.000</td>
                <td><span class="badge badge-outline">Dikonfirmasi</span></td>
                <td><a href="#" class="action-link">Detail &#9656;</a></td>
              </tr>
              <tr>
                <td>123</td>
                <td>Nadia Putri</td>
                <td>Putih Gading Renda (S)</td>
                <td>0821-5656</td>
                <td class="font-bold">Landasan Ulin</td>
                <td class="font-bold">Rp 520.000</td>
                <td><span class="badge badge-outline">Dibatalkan</span></td>
                <td><a href="#" class="action-link">Detail &#9656;</a></td>
              </tr>
              <tr>
                <td>123</td>
                <td>Rina Kusuma</td>
                <td>Rose Dusty Satin (M)</td>
                <td>0821-2121</td>
                <td class="font-bold">Banjarmasin</td>
                <td class="font-bold">Rp 230.000</td>
                <td><span class="badge badge-outline">Tertunda</span></td>
                <td><a href="#" class="action-link">Detail &#9656;</a></td>
              </tr>
              <tr>
                <td>123</td>
                <td>Fitri Handayani</td>
                <td>Kutubaru Batik Sogan (L)</td>
                <td>0821-3546</td>
                <td class="font-bold">Banjarbaru</td>
                <td class="font-bold">Rp 440.000</td>
                <td><span class="badge badge-outline">Selesai</span></td>
                <td><a href="#" class="action-link">Detail &#9656;</a></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Navigasi halaman tabel pesanan -->
        <div class="pagination-wrapper">
          <span>Menampilkan 1–7 dari 42 pesanan</span>
          <div class="pagination-controls">
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">Next</button>
          </div>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
