<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - cabil.rent Admin Panel</title>
  
  <!-- CSS Khusus Halaman Dashboard (Vanilla CSS) -->
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  
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

        <!-- Menu navigasi sidebar -->
        <nav class="sidebar-nav">
          <!-- Menu dashboard -->
          <a href="{{ url('/dashboard') }}" class="nav-item active">
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
        <a href="{{ url('/login') }}" class="user-profile" title="Klik untuk Logout / Ganti Akun">
          <div class="user-avatar">I</div>
          <div class="user-info">
            <span class="user-name">{{ Auth::user()->name ?? 'Ihsan' }}</span>
            <span class="user-role">{{ Auth::user()->role ?? 'Pemilik' }}</span>
          </div>
        </a>
      </div>
    </aside>

    <!-- Konten utama dashboard -->
    <main class="main-wrapper">
      <!-- Bagian atas judul halaman dan tombol -->
      <div class="top-header">
        <div class="header-title-box">
          <svg class="header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
          </svg>
          <div>
            <h1 class="header-title">Dashboard</h1>
            <p class="header-desc">Kelola kelompok kebaya yang disewakan</p>
          </div>
        </div>

        <div class="header-actions">
          <!-- Kolom pencarian -->
          <div class="search-bar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" class="search-input" placeholder="Cari Pesanan atau Produk">
          </div>

          <!-- Tombol tambah sewa baru -->
          <button class="btn-primary">
            <span>+</span> Sewa baru
          </button>
        </div>
      </div>

      <!-- Kartu ringkasan statistik -->
      <div class="stat-cards-grid">
        <!-- Ringkasan sewa berjalan -->
        <div class="stat-card">
          <span class="stat-label">Sewa Berjalan</span>
          <div class="stat-value">{{ $sewaBerjalan ?? '45 Gaun' }}</div>
          <div class="stat-subtext">&nbsp;</div>
        </div>

        <!-- Ringkasan pesanan aktif -->
        <div class="stat-card">
          <span class="stat-label">Pesanan Aktif</span>
          <div class="stat-value">{{ $pesananAktif ?? '42' }}</div>
          <div class="stat-subtext">
            <span class="stat-trend">&#9650; 8 pesanan baru</span>
          </div>
        </div>

        <!-- Ringkasan total produk -->
        <div class="stat-card">
          <span class="stat-label">Total Produk</span>
          <div class="stat-value">{{ $totalProduk ?? '143' }}</div>
          <div class="stat-subtext">100 tersedia dari 3 kategori</div>
        </div>

        <!-- Ringkasan keterlambatan pengembalian -->
        <div class="stat-card stat-alert">
          <span class="stat-label">Terlambat Kembali</span>
          <div class="stat-value">{{ $terlambatKembali ?? '5' }}</div>
          <div class="stat-subtext">Ada tindakan</div>
        </div>
      </div>

      <!-- Bagian tengah: grafik pendapatan dan kategori -->
      <div class="dashboard-middle-row">
        <!-- Grafik pendapatan 6 bulan terakhir -->
        <div class="card-panel">
          <div class="card-panel-header">
            <h3 class="card-panel-title">Pendapatan 6 Bulan Terakhir</h3>
          </div>

          <div class="chart-container">
            <div class="chart-grid-lines">
              <div class="chart-grid-line"></div>
              <div class="chart-grid-line"></div>
              <div class="chart-grid-line"></div>
              <div class="chart-grid-line"></div>
            </div>

            <!-- Batang grafik per bulan -->
            <div class="chart-bar-group">
              <div class="chart-bar" style="height: 70px;"></div>
              <span class="chart-label">Apr</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar" style="height: 55px;"></div>
              <span class="chart-label">Mei</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar" style="height: 120px;"></div>
              <span class="chart-label">Jun</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar" style="height: 80px;"></div>
              <span class="chart-label">Jul</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar" style="height: 145px;"></div>
              <span class="chart-label">Agu</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar" style="height: 120px;"></div>
              <span class="chart-label">Sep</span>
            </div>
            <div class="chart-bar-group">
              <div class="chart-bar active" style="height: 165px;"></div>
              <span class="chart-label">Okt</span>
            </div>
          </div>
        </div>

        <!-- Daftar persentase produk per kategori -->
        <div class="card-panel">
          <div class="card-panel-header">
            <h3 class="card-panel-title">Kategori Produk</h3>
            <a href="{{ url('/kategori') }}" class="link-action">Kelola</a>
          </div>

          <div class="progress-list">
            <!-- Kategori paket kebaya -->
            <div class="progress-item">
              <div class="progress-item-header">
                <span class="progress-item-title">Paket Kebaya</span>
                <span class="progress-item-count">78 produk</span>
              </div>
              <div class="progress-track">
                <div class="progress-fill fill-dark" style="width: 85%;"></div>
              </div>
            </div>

            <!-- Kategori heels -->
            <div class="progress-item">
              <div class="progress-item-header">
                <span class="progress-item-title">Heels</span>
                <span class="progress-item-count">62 produk</span>
              </div>
              <div class="progress-track">
                <div class="progress-fill fill-brown" style="width: 70%;"></div>
              </div>
            </div>

            <!-- Kategori kemben -->
            <div class="progress-item">
              <div class="progress-item-header">
                <span class="progress-item-title">Kemben</span>
                <span class="progress-item-count">44 produk</span>
              </div>
              <div class="progress-track">
                <div class="progress-fill fill-pink" style="width: 50%;"></div>
              </div>
            </div>

            <!-- Kategori baju adat -->
            <div class="progress-item">
              <div class="progress-item-header">
                <span class="progress-item-title">Adat</span>
                <span class="progress-item-count">26 produk</span>
              </div>
              <div class="progress-track">
                <div class="progress-fill fill-terracotta" style="width: 30%;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bagian bawah: tabel pesanan terbaru -->
      <div class="table-card">
        <div class="card-panel-header">
          <h3 class="card-panel-title">Pesanan Terbaru</h3>
          <a href="{{ url('/pesanan') }}" class="link-action">Lihat semua</a>
        </div>

        <div class="table-responsive">
          <table class="custom-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Periode</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>123</td>
                <td>Hailey Bieber</td>
                <td>Brokat Maroon (M)</td>
                <td>12–14 Okt</td>
                <td class="font-bold">Rp 750.000</td>
                <td><span class="badge badge-paid">Dibayar</span></td>
              </tr>
              <tr>
                <td>321</td>
                <td>Billie Eilis</td>
                <td>Tulle Sage Green (S)</td>
                <td>10–11 Okt</td>
                <td class="font-bold">Rp 400.000</td>
                <td><span class="badge badge-waiting">Menunggu</span></td>
              </tr>
              <tr>
                <td>54325</td>
                <td>Ayu Ting-Ting</td>
                <td>Encim Gold Klasik (L)</td>
                <td>08–09 Okt</td>
                <td class="font-bold">Rp 560.000</td>
                <td><span class="badge badge-rented">Disewa</span></td>
              </tr>
              <tr>
                <td>999</td>
                <td>Maya Sari</td>
                <td>Modern Navy Lace (M)</td>
                <td>05–06 Okt</td>
                <td class="font-bold">Rp 480.000</td>
                <td><span class="badge badge-late">Terlambat</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
