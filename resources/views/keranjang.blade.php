<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang - Cabil.Rent</title>
  
  <!-- CSS Khusus Halaman Keranjang -->
  <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
  
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
          <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
            <span>Dashboard</span>
          </a>

          <!-- Menu kategori -->
          <a href="{{ url('/kategori') }}" class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>Kategori</span>
          </a>

          <!-- Menu produk -->
          <a href="{{ url('/produk') }}" class="nav-item {{ request()->is('produk*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
            </svg>
            <span>Produk</span>
          </a>

          <!-- Menu pesanan -->
          <a href="{{ url('/pesanan') }}" class="nav-item {{ request()->is('pesanan*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="5" width="20" height="14" rx="2"></rect>
              <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            <span>Pesanan</span>
          </a>

          <!-- Menu keranjang -->
          <a href="{{ url('/keranjang') }}" class="nav-item {{ request()->is('keranjang*') ? 'active' : '' }}">
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

    <!-- Konten utama keranjang -->
    <main class="main-wrapper">
      <!-- Bagian atas judul halaman -->
      <div class="top-header">
        <div class="header-title-box">
          <svg class="header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
          <div>
            <h1 class="header-title">Keranjang</h1>
            <p class="header-desc">Total {{ $totalBaskets ?? $baskets->count() }} item keranjang tersimpan di database</p>
          </div>
        </div>

        <div class="header-actions">
          <a href="{{ url('/produk') }}" class="btn-primary">
            <span>+</span> Sewa Produk Baru
          </a>
        </div>
      </div>

      <!-- Notifikasi jika berhasil menghapus / aksi -->
      @if (session('success'))
        <div style="background-color: #e8f5e9; color: #2e7d32; padding: 12px 18px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; border: 1px solid #c8e6c9;">
          {{ session('success') }}
        </div>
      @endif

      <!-- Filter dan pencarian -->
      <div class="pesanan-filter-bar">
        <form method="GET" action="{{ url('/keranjang') }}" class="search-bar" style="width: 100%; max-width: 380px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" name="search" class="search-input" style="width: 100%;" placeholder="Cari id, nama pelanggan, atau produk" value="{{ request('search') }}">
        </form>
      </div>

      <!-- Tabel data keranjang -->
      <div class="table-card">
        <div class="table-responsive">
          <table class="custom-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Harga Sewa</th>
                <th>Jumlah</th>
                <th>Tanggal Acara</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($baskets as $basket)
                <tr>
                  <td>#{{ $basket->id }}</td>
                  <td>
                    <div class="font-bold">{{ $basket->user->nama_lengkap ?? ($basket->user->username ?? 'Pelanggan #' . $basket->user_id) }}</div>
                    <span style="font-size: 11px; color: #8e8077;">{{ $basket->user->no_telp ?? ($basket->user->email ?? '-') }}</span>
                  </td>
                  <td>
                    <div class="product-cell">
                      <img src="{{ asset('images/' . ($basket->product->foto_produk ?? 'produk-brokat-maroon.png')) }}" alt="{{ $basket->product->nama_produk ?? 'Produk' }}" class="product-thumb">
                      <div>
                        <div class="font-bold">{{ $basket->product->nama_produk ?? 'Produk #' . $basket->produk_id }}</div>
                        <span style="font-size: 11px; color: #8e8077;">{{ $basket->product->category->nama_kategori ?? 'Kategori' }}</span>
                      </div>
                    </div>
                  </td>
                  <td>Rp {{ number_format($basket->product->harga_sewa ?? 0, 0, ',', '.') }}</td>
                  <td>{{ $basket->jumlah }} item</td>
                  <td>
                    @if ($basket->tanggal_acara)
                      {{ \Carbon\Carbon::parse($basket->tanggal_acara)->format('d M Y') }}
                    @else
                      -
                    @endif
                  </td>
                  <td class="font-bold">
                    Rp {{ number_format(($basket->product->harga_sewa ?? 0) * $basket->jumlah, 0, ',', '.') }}
                  </td>
                  <td>
                    @if ($basket->order)
                      <span class="badge badge-outline" style="background-color: #623e2b; color: #ffffff; border-color: #623e2b;">
                        {{ ucfirst($basket->order->status_pesan) }}
                      </span>
                    @else
                      <span class="badge badge-outline" style="background-color: #D8C3A5; color: #3D2010; border-color: #D8C3A5;">
                        Di Keranjang
                      </span>
                    @endif
                  </td>
                  <td>
                    <form action="{{ route('keranjang.destroy', $basket->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="action-danger">Hapus</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" style="text-align: center; padding: 35px 20px; color: #8e8077;">
                    Belum ada data keranjang yang tersimpan di database.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if(method_exists($baskets, 'links') && $baskets->hasPages())
          <div class="pagination-wrapper" style="margin-top: 20px;">
            <span>Menampilkan {{ $baskets->firstItem() ?? 0 }}–{{ $baskets->lastItem() ?? 0 }} dari {{ $baskets->total() }} data</span>
            <div>
              {{ $baskets->links() }}
            </div>
          </div>
        @endif
      </div>
    </main>
  </div>

</body>
</html>
