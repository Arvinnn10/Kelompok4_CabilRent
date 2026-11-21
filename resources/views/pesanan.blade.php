<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan - Cabil.Rent</title>

  <!-- Tata letak admin (sidebar, bar atas, kartu, badge) dipakai bersama dashboard -->
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <!-- CSS khusus halaman Pesanan -->
  <link rel="stylesheet" href="{{ asset('css/pesanan.css') }}">
  <link rel="stylesheet" href="{{ asset('css/transisi.css') }}">
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
          <a href="{{ url('/dashboard') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="3" width="7" height="5" rx="1.5"></rect>
              <rect x="14" y="12" width="7" height="9" rx="1.5"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
            <span>Dashboard</span>
          </a>
          <a href="{{ url('/pesanan') }}" class="nav-item active">
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
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            <polyline points="3.3 7 12 12 20.7 7"></polyline>
            <line x1="12" y1="22" x2="12" y2="12"></line>
          </svg>
        </span>

        <div class="search-bar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" placeholder="Cari nama kebaya" aria-label="Cari nama kebaya">
        </div>

        <div class="topbar-actions">
          <!-- Notifikasi -->
          <div class="dropdown">
            <button type="button" class="icon-btn btn-notif" id="btn-notif" title="Notifikasi"
                    aria-haspopup="true" aria-expanded="false" aria-controls="menu-notif">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
            </button>

            <div class="dropdown-menu notif-menu" id="menu-notif" hidden>
              <div class="notif-head">
                <span class="notif-title">Notifikasi</span>
                <button type="button" class="link-btn" id="btn-baca">Tandai dibaca</button>
              </div>
              <ul class="notif-list">
                <!-- BACKEND: ganti dengan daftar notifikasi asli -->
                <li class="notif-item is-unread">
                  <p class="notif-judul">Pesanan baru CR-1044</p>
                  <p class="notif-detail">Bella Safira · 12 Des</p>
                </li>
                <li class="notif-item is-unread">
                  <p class="notif-judul">Pesanan baru CR-1043</p>
                  <p class="notif-detail">Anisa Rahma · 3 Des</p>
                </li>
                <li class="notif-item is-unread">
                  <p class="notif-judul">Aura Heels tinggal 2</p>
                  <p class="notif-detail">Perlu restok</p>
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
              </div>
              <button type="button" class="akun-keluar" data-open-logout>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                  <polyline points="16 17 21 12 16 7"></polyline>
                  <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Keluar</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      <main class="main-content">
        <h1 class="page-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2z"></path>
            <line x1="9" y1="8" x2="15" y2="8"></line>
            <line x1="9" y1="12" x2="15" y2="12"></line>
          </svg>
          Pesanan
        </h1>

        @if (session('success'))
          <p class="flash-success">{{ session('success') }}</p>
        @endif

        <!-- Kartu ringkasan -->
        <section class="ps-stats">
          <div class="stat-card">
            <p class="ps-stat-label">Total Pesanan</p>
            <p class="ps-stat-value">{{ $totalOrders ?? 0 }}</p>
          </div>
          <!-- BACKEND: tiga kartu di bawah masih angka contoh dari desain -->
          <div class="stat-card">
            <p class="ps-stat-label">Perlu Dikonfirmasi</p>
            <p class="ps-stat-value">2</p>
          </div>
          <div class="stat-card">
            <p class="ps-stat-label">Sedang Disewa</p>
            <p class="ps-stat-value">0</p>
          </div>
          <div class="stat-card">
            <p class="ps-stat-label">Pendapatan Selesai</p>
            <p class="ps-stat-value">Rp 450.000</p>
          </div>
        </section>

        <!-- Pencarian -->
        <form method="GET" action="{{ url('/pesanan') }}" class="ps-search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID atau Nama" aria-label="Cari ID atau Nama">
        </form>

        <!-- Tabel pesanan -->
        <section class="panel table-panel ps-table-panel">
          <div class="table-scroll">
            <table class="order-table ps-table">
              <thead>
                <tr>
                  <th>ID Pesanan</th>
                  <th>Pelanggan</th>
                  <th>Item</th>
                  <th>Tgl Acara</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($orders as $order)
                  <tr>
                    <td>
                      <span class="ps-id">CR-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                      <span class="ps-sub">{{ $order->created_at->locale('id')->translatedFormat('j M, H:i') }}</span>
                    </td>
                    <td>
                      <span class="ps-nama">{{ $order->user->nama_lengkap ?? $order->user->username ?? 'Pelanggan' }}</span>
                      <span class="ps-sub">{{ $order->no_whatsapp }}</span>
                    </td>
                    <td>
                      <span class="ps-item">{{ $order->product->nama_produk ?? $order->desk_pemesanan ?? '-' }}</span>
                      <span class="ps-sub">{{ $order->basket->jumlah ?? 1 }} item</span>
                    </td>
                    <td class="ps-tgl">{{ $order->basket && $order->basket->tanggal_acara ? $order->basket->tanggal_acara->locale('id')->translatedFormat('j M Y') : '-' }}</td>
                    <td class="ps-total">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td>
                      <span class="badge status-{{ $order->status_pesan }}">{{ ['pending' => 'Menunggu', 'dikonfirmasi' => 'Dikonfirmasi', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'][$order->status_pesan] ?? ucfirst($order->status_pesan) }}</span>
                    </td>
                    <td>
                      <div class="ps-aksi">
                        @if ($order->status_pesan === 'pending')
                          <form action="{{ route('pesanan.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pesan" value="dikonfirmasi">
                            <button type="submit" class="btn-aksi">Konfirmasi</button>
                          </form>
                        @elseif ($order->status_pesan === 'dikonfirmasi')
                          <form action="{{ route('pesanan.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_pesan" value="selesai">
                            <button type="submit" class="btn-aksi">Selesaikan</button>
                          </form>
                        @endif

                        <!-- Tombol mata: membuka panel detail (js/pesanan.js membaca data-* di bawah) -->
                        <button type="button" class="btn-lihat" title="Lihat detail"
                                data-id="CR-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}"
                                data-status="{{ $order->status_pesan }}"
                                data-action="{{ route('pesanan.status', $order->id) }}"
                                data-nama="{{ $order->user->nama_lengkap ?? $order->user->username ?? 'Pelanggan' }}"
                                data-whatsapp="{{ $order->no_whatsapp }}"
                                data-tgl-acara="{{ $order->basket && $order->basket->tanggal_acara ? $order->basket->tanggal_acara->locale('id')->translatedFormat('j M Y') : '-' }}"
                                data-dipesan="{{ $order->created_at->locale('id')->translatedFormat('j M, H:i') }}"
                                data-produk="{{ $order->product->nama_produk ?? $order->desk_pemesanan ?? '-' }}"
                                data-foto="{{ $order->product && $order->product->foto_produk ? (str_starts_with($order->product->foto_produk, 'http') ? $order->product->foto_produk : asset('images/' . (str_starts_with($order->product->foto_produk, 'koleksi/') || file_exists(public_path('images/' . $order->product->foto_produk)) ? '' : 'koleksi/') . $order->product->foto_produk)) : '' }}"
                                data-harga="{{ $order->product->harga_sewa ?? 0 }}"
                                data-jumlah="{{ $order->basket->jumlah ?? 1 }}"
                                data-total="{{ $order->total_harga }}">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="cell-kosong">Belum ada data pesanan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Navigasi halaman -->
          <div class="ps-pagination">
            <span class="ps-pagination-info">Menampilkan {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} pesanan</span>
            <nav class="ps-pages" aria-label="Halaman">
              @if ($orders->onFirstPage())
                <span class="ps-page is-disabled" aria-disabled="true">&lsaquo;</span>
              @else
                <a href="{{ $orders->previousPageUrl() }}" class="ps-page" aria-label="Sebelumnya">&lsaquo;</a>
              @endif

              @foreach (range(1, $orders->lastPage()) as $halaman)
                <a href="{{ $orders->url($halaman) }}" class="ps-page {{ $halaman === $orders->currentPage() ? 'is-active' : '' }}">{{ $halaman }}</a>
              @endforeach

              @if ($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="ps-page" aria-label="Berikutnya">&rsaquo;</a>
              @else
                <span class="ps-page is-disabled" aria-disabled="true">&rsaquo;</span>
              @endif
            </nav>
          </div>
        </section>
      </main>
    </div>
  </div>

  <!-- Panel detail pesanan (terbuka dari tombol mata) -->
  <div class="drawer-overlay" id="drawer-overlay" hidden></div>
  <aside class="drawer" id="drawer" role="dialog" aria-modal="true" aria-labelledby="drawer-id" hidden>
    <div class="drawer-head">
      <h2 class="drawer-id" id="drawer-id">CR-0000</h2>
      <span class="badge" id="drawer-badge">Menunggu</span>
      <button type="button" class="drawer-x" id="drawer-x" aria-label="Tutup">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>

    <form class="drawer-form" id="drawer-form" method="POST" action="">
      @csrf
      @method('PATCH')

      <div class="drawer-body">
        <div class="drawer-cust">
          <span class="avatar avatar-light" id="drawer-avatar">BS</span>
          <div class="drawer-cust-info">
            <p class="drawer-nama" id="drawer-nama"></p>
            <p class="drawer-wa" id="drawer-wa"></p>
          </div>
          <a href="#" class="btn-chat" id="drawer-chat" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.5 8.5 0 0 1-3.8-.9L3 21l1.9-5.2A8.4 8.4 0 0 1 3.9 11.5a8.5 8.5 0 0 1 17.1 0z"></path>
            </svg>
            Chat
          </a>
        </div>

        <dl class="drawer-meta">
          <dt>Tanggal acara</dt>
          <dd id="drawer-tgl"></dd>
          <dt>Dipesan</dt>
          <dd id="drawer-dipesan"></dd>
        </dl>

        <h3 class="drawer-sub">Item pesanan</h3>
        <div class="drawer-item">
          <div class="drawer-foto"><img src="" alt="" id="drawer-foto"></div>
          <span class="drawer-produk" id="drawer-produk"></span>
          <span class="drawer-harga" id="drawer-harga"></span>
        </div>

        <div class="drawer-rows">
          <div class="drawer-row"><span>Subtotal sewa</span><span id="drawer-subtotal"></span></div>
          <div class="drawer-row"><span>Deposit (dikembalikan)</span><span id="drawer-deposit"></span></div>
          <div class="drawer-row drawer-total"><span>Total dibayar</span><span id="drawer-total"></span></div>
        </div>

        <label class="drawer-label" for="drawer-status">Ubah status</label>
        <select name="status_pesan" id="drawer-status" class="drawer-select">
          <option value="pending">Menunggu</option>
          <option value="dikonfirmasi">Dikonfirmasi</option>
          <option value="selesai">Selesai</option>
          <option value="dibatalkan">Dibatalkan</option>
        </select>
      </div>

      <div class="drawer-foot">
        <button type="button" class="btn-batal" id="drawer-tutup">Tutup</button>
        <button type="submit" class="btn-ya">Simpan</button>
      </div>
    </form>
  </aside>

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
  <script src="{{ asset('js/pesanan.js') }}"></script>
  <script src="{{ asset('js/transisi.js') }}"></script>
</body>
</html>
