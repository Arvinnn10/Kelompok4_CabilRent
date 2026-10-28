<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan - Cabil.Rent</title>
  
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
              @forelse ($orders as $order)
                <tr>
                  <td>#{{ $order->id }}</td>
                  <td>{{ $order->user->nama_lengkap ?? $order->user->username ?? 'Pelanggan' }}</td>
                  <td>{{ $order->product->nama_produk ?? $order->desk_pemesanan ?? '-' }}</td>
                  <td>{{ $order->no_whatsapp ?? '-' }}</td>
                  <td class="font-bold">{{ $order->domisili ?? '-' }}</td>
                  <td class="font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                  <td><span class="badge badge-outline">{{ ucfirst($order->status_pesan) }}</span></td>
                  <td><a href="#" class="action-link">Detail &#9656;</a></td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" style="text-align: center; padding: 30px; color: #8e8077;">
                    Belum ada data pesanan di database.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if(method_exists($orders, 'links') && $orders->hasPages())
          <div class="pagination-wrapper">
            <span>Menampilkan {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} pesanan</span>
            <div>
              {{ $orders->links() }}
            </div>
          </div>
        @endif
      </div>
    </main>
  </div>

</body>
</html>
