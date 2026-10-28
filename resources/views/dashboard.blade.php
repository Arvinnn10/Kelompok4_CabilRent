<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Cabil.Rent</title>
  
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
          <img src="{{ asset('images/logo.png') }}" alt="Logo cabil.rent" class="sidebar-logo">
          <span class="sidebar-brand">CABIL.RENT</span>
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
            <span>+</span> Catat Sewa 
          </button>
        </div>
      </div>

      <!-- Kartu ringkasan statistik -->
      <div class="stat-cards-grid">
        <!-- Ringkasan sewa berjalan -->
        <div class="stat-card">
          <span class="stat-label">Sewa Berjalan</span>
          <div class="stat-value">{{ $sewaBerjalan ?? 0 }}</div>
          <div class="stat-subtext">Pesanan sedang disewa</div>
        </div>

        <!-- Ringkasan pesanan aktif -->
        <div class="stat-card">
          <span class="stat-label">Pesanan Aktif</span>
          <div class="stat-value">{{ $pesananAktif ?? 0 }}</div>
          <div class="stat-subtext">
            <span class="stat-trend">&#9650; Menunggu & Dikonfirmasi</span>
          </div>
        </div>

        <!-- Ringkasan total produk -->
        <div class="stat-card">
          <span class="stat-label">Total Produk</span>
          <div class="stat-value">{{ $totalProduk ?? 0 }}</div>
          <div class="stat-subtext">{{ $produkTersedia ?? 0 }} tersedia dari {{ $totalKategori ?? 0 }} kategori</div>
        </div>

        <!-- Ringkasan keterlambatan pengembalian -->
        <div class="stat-card stat-alert">
          <span class="stat-label">Terlambat Kembali</span>
          <div class="stat-value">{{ $terlambatKembali ?? 0 }}</div>
          <div class="stat-subtext">Penyewaan aktif</div>
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
            @forelse($kategoriList as $kat)
            <div class="progress-item">
              <div class="progress-item-header">
                <span class="progress-item-title">{{ $kat->nama_kategori }}</span>
                <span class="progress-item-count">{{ $kat->products_count }} produk</span>
              </div>
              <div class="progress-track">
                @php
                  $percent = ($totalProduk > 0) ? min(100, round(($kat->products_count / $totalProduk) * 100)) : 0;
                @endphp
                <div class="progress-fill fill-dark" style="width: {{ $percent }}%;"></div>
              </div>
            </div>
            @empty
            <p style="padding: 10px; color: #888; font-size: 13px;">Belum ada kategori.</p>
            @endforelse
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
              @forelse ($pesananTerbaru as $order)
                <tr>
                  <td>#{{ $order->id }}</td>
                  <td>{{ $order->user->nama_lengkap ?? $order->user->username ?? 'Pelanggan' }}</td>
                  <td>{{ $order->product->nama_produk ?? $order->desk_pemesanan ?? '-' }}</td>
                  <td>{{ $order->basket && $order->basket->tanggal_acara ? \Carbon\Carbon::parse($order->basket->tanggal_acara)->format('d M Y') : $order->created_at->format('d M Y') }}</td>
                  <td class="font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                  <td>
                    <span class="badge {{ $order->status_pesan === 'dikonfirmasi' ? 'badge-paid' : 'badge-waiting' }}">
                      {{ ucfirst($order->status_pesan) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" style="text-align: center; padding: 25px 20px; color: #8e8077;">
                    Belum ada pesanan terbaru.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Bagian tabel keranjang terbaru -->
      <div class="table-card" style="margin-top: 22px;">
        <div class="card-panel-header">
          <h3 class="card-panel-title">Keranjang Terbaru</h3>
          <a href="{{ url('/keranjang') }}" class="link-action">Lihat semua</a>
        </div>

        <div class="table-responsive">
          <table class="custom-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Tanggal Acara</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($keranjangTerbaru as $basket)
                <tr>
                  <td>#{{ $basket->id }}</td>
                  <td>
                    <div class="font-bold">{{ $basket->user->nama_lengkap ?? $basket->user->username ?? 'Pelanggan' }}</div>
                    <span style="font-size: 11px; color: #8e8077;">{{ $basket->user->no_telp ?? '-' }}</span>
                  </td>
                  <td>{{ $basket->product->nama_produk ?? '-' }}</td>
                  <td>{{ $basket->jumlah }} item</td>
                  <td>{{ $basket->tanggal_acara ? \Carbon\Carbon::parse($basket->tanggal_acara)->format('d M Y') : '-' }}</td>
                  <td class="font-bold">Rp {{ number_format(($basket->product->harga_sewa ?? 0) * $basket->jumlah, 0, ',', '.') }}</td>
                  <td>
                    @if ($basket->order)
                      <span class="badge badge-paid">{{ ucfirst($basket->order->status_pesan) }}</span>
                    @else
                      <span class="badge badge-waiting">Di Keranjang</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" style="text-align: center; padding: 25px 20px; color: #8e8077;">
                    Belum ada data keranjang.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
