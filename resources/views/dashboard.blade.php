<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Cabil.Rent</title>

  <!-- CSS Khusus Halaman Dashboard (Vanilla CSS) -->
  <link rel="stylesheet" href="{{ asset_versioned('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
</head>
<body>

  <div class="app-container" id="app">
    <!-- Sidebar navigasi -->
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
          <a href="{{ url('/dashboard') }}" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="3" width="7" height="5" rx="1.5"></rect>
              <rect x="14" y="12" width="7" height="9" rx="1.5"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
            <span>Dashboard</span>
          </a>
          <a href="{{ url('/pesanan') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2z"></path>
              <line x1="9" y1="8" x2="15" y2="8"></line>
              <line x1="9" y1="12" x2="15" y2="12"></line>
            </svg>
            <span>Pesanan</span>
          </a>

          <p class="nav-section">KATALOG</p>
          <a href="{{ url('/produk') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
              <polyline points="3.3 7 12 12 20.7 7"></polyline>
              <line x1="12" y1="22" x2="12" y2="12"></line>
            </svg>
            <span>Produk</span>
          </a>
          <a href="{{ url('/kategori') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
              <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            <span>Kategori</span>
          </a>

          <p class="nav-section">PENGGUNA</p>
          <a href="{{ url('/pengguna') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="8" r="4"></circle>
              <path d="M2 21a7 7 0 0 1 14 0"></path>
              <path d="M16 4a4 4 0 0 1 0 8"></path>
              <path d="M22 21a7 7 0 0 0-4-6.3"></path>
            </svg>
            <span>Pengguna</span>
          </a>
        </nav>
      </div>

      <!-- Profil admin dan tombol keluar -->
      <div class="sidebar-footer">
        <div class="admin-card">
          <span class="avatar avatar-dark">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->username ?? 'A', 0, 1)) }}</span>
          <div class="admin-info">
            <span class="admin-name">{{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</span>
            <span class="admin-email">{{ Auth::user()->email }}</span>
          </div>
        </div>
        <button type="button" class="btn-keluar" data-open-logout>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <div class="main-area">
      <!-- Bar atas -->
      <header class="topbar">
        <span class="icon-btn icon-static" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="3" width="7" height="5" rx="1.5"></rect>
            <rect x="14" y="12" width="7" height="9" rx="1.5"></rect>
            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
          </svg>
        </span>

        <div class="topbar-actions" style="margin-left: auto;">
          <!-- Notifikasi -->
          <div class="dropdown">
            <button type="button" class="icon-btn btn-notif" id="btn-notif" title="Notifikasi"
                    aria-haspopup="true" aria-expanded="false" aria-controls="menu-notif">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
              <span class="notif-dot" id="notif-dot"></span>
            </button>

            <div class="dropdown-menu notif-menu" id="menu-notif" hidden>
              <div class="notif-head">
                <span class="notif-title">Notifikasi</span>
                <button type="button" class="link-btn" id="btn-baca">Tandai dibaca</button>
              </div>
              <ul class="notif-list">
                <li class="notif-item is-unread">
                  <p class="notif-judul">Panel Admin CabilRent</p>
                  <p class="notif-detail">Selamat datang di dashboard admin</p>
                </li>
              </ul>
            </div>
          </div>

          <!-- Akun -->
          <div class="dropdown">
            <button type="button" class="avatar avatar-light avatar-btn" id="btn-akun" title="Akun"
                    aria-haspopup="true" aria-expanded="false" aria-controls="menu-akun">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->username ?? 'A', 0, 1)) }}</button>

            <div class="dropdown-menu akun-menu" id="menu-akun" hidden>
              <div class="akun-head">
                <p class="akun-nama">{{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</p>
                <p class="akun-email">{{ Auth::user()->email }}</p>
                <span style="font-size: 11px; color: #8c786c; font-weight: 600; text-transform: uppercase;">Administrator</span>
              </div>
            </div>
          </div>
        </div>
      </header>

      <main class="main-content">
        <h1 class="page-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <rect x="3" y="3" width="7" height="9" rx="2"></rect>
            <rect x="14" y="3" width="7" height="5" rx="2"></rect>
            <rect x="14" y="12" width="7" height="9" rx="2"></rect>
            <rect x="3" y="16" width="7" height="5" rx="2"></rect>
          </svg>
          Dashboard
        </h1>

        <!-- Kartu ringkasan -->
        <section class="stat-grid">
          <div class="stat-card stat-dark">
            <div class="stat-top">
              <span class="stat-label">Pendapatan bulan ini</span>
              <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                  <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
              </span>
            </div>
            <p class="stat-value">Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}</p>
            <div class="stat-foot">
              <span>Total selesai: Rp {{ number_format($pendapatanSemua ?? 0, 0, ',', '.') }}</span>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <span class="stat-label">Pesanan baru</span>
              <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2z"></path>
                  <line x1="9" y1="8" x2="15" y2="8"></line>
                  <line x1="9" y1="12" x2="15" y2="12"></line>
                </svg>
              </span>
            </div>
            <p class="stat-value">{{ $pesananBaruCount ?? 0 }}</p>
            <div class="stat-foot">
              <span class="stat-strong">Perlu diproses</span>
              <span>Status pending</span>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <span class="stat-label">Sedang disewa</span>
              <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                </svg>
              </span>
            </div>
            <p class="stat-value">{{ $sewaBerjalan ?? 0 }}</p>
            <div class="stat-foot">
              <span class="stat-strong">Dikonfirmasi</span>
              <span>Pantau pengembalian</span>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-top">
              <span class="stat-label">Total pengguna</span>
              <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="9" cy="8" r="4"></circle>
                  <path d="M2 21a7 7 0 0 1 14 0"></path>
                  <path d="M16 4a4 4 0 0 1 0 8"></path>
                  <path d="M22 21a7 7 0 0 0-4-6.3"></path>
                </svg>
              </span>
            </div>
            <p class="stat-value">{{ $totalPenggunaCount ?? 0 }}</p>
            <div class="stat-foot">
              <span class="stat-strong">Pelanggan terdaftar</span>
              <span>Non-admin</span>
            </div>
          </div>
        </section>

        <!-- Grafik pendapatan dan status pesanan -->
        <section class="middle-grid">
          <div class="panel">
            <h2 class="panel-title">Pendapatan</h2>
            <p class="panel-amount">Rp 15.526.000</p>

            <div class="chart">
              <div class="chart-y">
                <span>900 rb</span>
                <span>600 rb</span>
                <span>300 rb</span>
                <span>0</span>
              </div>
              <div class="chart-plot">
                <div class="chart-lines">
                  <span></span><span></span><span></span><span></span>
                </div>
                <svg class="chart-svg" viewBox="0 0 600 130" preserveAspectRatio="none" aria-label="Grafik pendapatan harian">
                  <polygon class="chart-area" points="0,130 0,86.7 20.7,65 41.4,43.3 62.1,57.8 82.8,46.2 103.4,36.1 124.1,60.7 144.8,57.8 165.5,54.9 186.2,79.4 206.9,72.2 227.6,89.6 248.3,75.1 269,57.8 289.7,69.3 310.3,50.6 331,26 351.7,37.6 372.4,23.1 393.1,14.4 413.8,36.1 434.5,33.2 455.2,60.7 475.9,57.8 496.6,53.4 517.2,70.8 537.9,60.7 558.6,41.9 579.3,50.6 600,28.9 600,130"></polygon>
                  <polyline class="chart-line" points="0,86.7 20.7,65 41.4,43.3 62.1,57.8 82.8,46.2 103.4,36.1 124.1,60.7 144.8,57.8 165.5,54.9 186.2,79.4 206.9,72.2 227.6,89.6 248.3,75.1 269,57.8 289.7,69.3 310.3,50.6 331,26 351.7,37.6 372.4,23.1 393.1,14.4 413.8,36.1 434.5,33.2 455.2,60.7 475.9,57.8 496.6,53.4 517.2,70.8 537.9,60.7 558.6,41.9 579.3,50.6 600,28.9"></polyline>
                </svg>
                <div class="chart-x">
                  <span>12 Okt</span>
                  <span>17 Okt</span>
                  <span>22 Okt</span>
                  <span>27 Okt</span>
                  <span>1 Nov</span>
                  <span>6 Nov</span>
                  <span>10 Nov</span>
                </div>
              </div>
            </div>
          </div>

          <div class="panel">
            <h2 class="panel-title panel-title-bold">Status pesanan</h2>

            <div class="donut">
              <svg viewBox="0 0 100 100" aria-hidden="true">
                <circle class="donut-track" cx="50" cy="50" r="42"></circle>
                @if (isset($chartData) && ($totalOrdersCount ?? 0) > 0)
                  @foreach ($chartData as $segment)
                    @if ($segment['count'] > 0)
                      <circle class="donut-arc {{ $segment['class'] }}" cx="50" cy="50" r="42"
                              stroke-dasharray="{{ $segment['dasharray'] }}"
                              stroke-dashoffset="{{ $segment['dashoffset'] }}"></circle>
                    @endif
                  @endforeach
                @endif
              </svg>
              <div class="donut-center">
                <span class="donut-total">{{ $totalOrdersCount ?? 0 }}</span>
                <span class="donut-sub">pesanan</span>
              </div>
            </div>

            <ul class="legend">
              <li><span class="legend-dot status-menunggu"></span><span class="legend-label">Menunggu</span><span class="legend-count">{{ $countPending ?? 0 }}</span></li>
              <li><span class="legend-dot status-dikonfirmasi"></span><span class="legend-label">Dikonfirmasi</span><span class="legend-count">{{ $countDikonfirmasi ?? 0 }}</span></li>
              <li><span class="legend-dot status-disewa"></span><span class="legend-label">Sedang disewa</span><span class="legend-count">{{ $sewaBerjalan ?? 0 }}</span></li>
              <li><span class="legend-dot status-selesai"></span><span class="legend-label">Selesai</span><span class="legend-count">{{ $countSelesai ?? 0 }}</span></li>
              @if (($countDibatalkan ?? 0) > 0)
                <li><span class="legend-dot status-dibatalkan"></span><span class="legend-label">Dibatalkan</span><span class="legend-count">{{ $countDibatalkan ?? 0 }}</span></li>
              @endif
            </ul>
          </div>
        </section>

        <!-- Pesanan terbaru -->
        <section class="panel table-panel">
          <div class="table-head">
            <h2 class="panel-title panel-title-bold">Pesanan terbaru</h2>
            <a href="{{ url('/pesanan') }}" class="link-all">Lihat semua pesanan &rarr;</a>
          </div>

          <div class="table-scroll">
            <table class="order-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Pelanggan</th>
                  <th>Tgl acara</th>
                  <th>Total</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($pesananTerbaru as $order)
                  <tr>
                    <td class="cell-id">CR-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                      <div class="pelanggan">
                        <span class="avatar avatar-sm">{{ strtoupper(substr($order->user->nama_lengkap ?? $order->user->username ?? 'P', 0, 2)) }}</span>
                        <span>{{ $order->user->nama_lengkap ?? $order->user->username ?? 'Pelanggan' }}</span>
                      </div>
                    </td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td class="cell-total">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td><span class="badge status-{{ $order->status_pesan }}">{{ ucfirst($order->status_pesan) }}</span></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="cell-kosong">Belum ada pesanan terbaru.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </div>

  <!-- Konfirmasi keluar -->
  <div class="modal-overlay" id="modal-logout" hidden>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-logout-title">
      <h2 class="modal-title" id="modal-logout-title">Keluar dari panel admin?</h2>
      <p class="modal-text">Kamu perlu masuk lagi untuk mengelola produk dan pesanan.</p>
      <form action="{{ route('logout') }}" method="POST" class="modal-actions">
        @csrf
        <button type="button" class="btn-batal" data-close-logout>Batal</button>
        <button type="submit" class="btn-ya">Ya, keluar</button>
      </form>
    </div>
  </div>

  <script src="{{ asset('js/dashboard.js') }}"></script>
  <script src="{{ asset('js/transisi.js') }}"></script>
</body>
</html>
