<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Produk - Cabil.Rent</title>
  
  <!-- CSS Khusus Halaman Produk  -->
  <link rel="stylesheet" href="{{ asset('css/produk.css') }}">
  
  <!-- Font Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    .badge-toggle-btn {
      border: none;
      cursor: pointer;
      font-family: inherit;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      transition: opacity 0.2s;
    }
    .badge-toggle-btn:hover {
      opacity: 0.85;
    }
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(4px);
      z-index: 999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .modal-box {
      background: #ffffff;
      border-radius: 16px;
      max-width: 500px;
      width: 100%;
      padding: 28px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .form-group-admin {
      margin-bottom: 14px;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .form-group-admin label {
      font-size: 12px;
      font-weight: 600;
      color: #3d2010;
    }
    .form-group-admin input, .form-group-admin select, .form-group-admin textarea {
      padding: 10px 12px;
      border: 1px solid #d4c8bd;
      border-radius: 8px;
      font-size: 13px;
      outline: none;
    }
  </style>
</head>
<body>

  <div class="app-container">
    <!-- Sidebar navigasi -->
    <aside class="sidebar">
      <div>
        <div class="sidebar-header">
          <img src="{{ asset('images/logo.png') }}" alt="Logo cabil.rent" class="sidebar-logo" loading="lazy" decoding="async">
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

          <!-- Menu keranjang -->
          <a href="{{ url('/admin/keranjang') }}" class="nav-item">
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
            <p class="header-desc">{{ $totalProducts ?? 0 }} busana terdaftar &bull; {{ $availableProducts ?? 0 }} tersedia</p>
          </div>
        </div>

        <div class="header-actions">
          <button class="btn-primary" onclick="document.getElementById('modal-tambah-produk').style.display='flex'">
            <span>+</span> Tambah Produk
          </button>
        </div>
      </div>

      @if(session('success'))
        <div style="background-color: #e8f5e9; color: #2e7d32; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 13px;">
          {{ session('success') }}
        </div>
      @endif

      <!-- Kolom cari dan tombol filter kategori -->
      <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;">
        <form method="GET" action="{{ url('/produk') }}" class="search-bar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" name="search" class="search-input" placeholder="Cari nama busana" value="{{ request('search') }}" style="width: 280px;">
        </form>

        <div class="filter-tabs">
          <a href="{{ url('/produk') }}" class="filter-tab {{ !request('kategori') ? 'active' : '' }}">Semua</a>
          @foreach($categories as $cat)
            <a href="{{ url('/produk') }}?kategori={{ $cat->id }}" class="filter-tab {{ request('kategori') == $cat->id ? 'active' : '' }}">
              {{ $cat->nama_kategori }}
            </a>
          @endforeach
        </div>
      </div>

      <!-- Daftar produk -->
      <div class="product-grid">
        @forelse($products as $prod)
          @php
            $fotoPath = $prod->foto_produk;
            if (!str_starts_with($fotoPath, 'http')) {
                $fotoPath = asset('images/' . (str_starts_with($fotoPath, 'koleksi/') ? $fotoPath : 'koleksi/' . $fotoPath));
            }
          @endphp
          <div class="product-card">
            <div class="product-img-container">
              <img src="{{ $fotoPath }}" alt="{{ $prod->nama_produk }}" class="product-img" loading="lazy" decoding="async">
            </div>
            <h4 class="product-title">{{ $prod->nama_produk }}</h4>
            <p class="product-category-size">{{ $prod->category->nama_kategori ?? 'Kategori' }} &bull; {{ $prod->desk_produk ?: '-' }}</p>
            <div class="product-footer">
              <div class="product-price">Rp {{ number_format($prod->harga_sewa, 0, ',', '.') }}<span>/sewa</span></div>
              <form action="{{ route('produk.toggle', $prod->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="badge-toggle-btn {{ $prod->status_produk === 'tersedia' ? 'badge-available' : 'badge-waiting' }}" title="Klik untuk ubah status ketersediaan">
                  {{ $prod->status_produk === 'tersedia' ? 'Tersedia' : 'Disewa / Habis' }}
                </button>
              </form>
            </div>
          </div>
        @empty
          <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #8e8077;">
            Belum ada produk yang ditemukan.
          </div>
        @endforelse
      </div>

      @if(method_exists($products, 'links') && $products->hasPages())
        <div style="margin-top: 24px;">
          {{ $products->links() }}
        </div>
      @endif
    </main>
  </div>

  <!-- Modal Tambah Produk Baru -->
  <div id="modal-tambah-produk" class="modal-overlay">
    <div class="modal-box">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-size: 18px; color: #3d2010; font-weight: 700;">Tambah Produk Busana Baru</h3>
        <button type="button" style="background: none; border: none; font-size: 24px; cursor: pointer;" onclick="document.getElementById('modal-tambah-produk').style.display='none'">&times;</button>
      </div>

      <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group-admin">
          <label>Nama Produk *</label>
          <input type="text" name="nama_produk" placeholder="Misal: Kebaya Brokat Maroon" required>
        </div>

        <div class="form-group-admin">
          <label>Kategori *</label>
          <select name="kategori_id" required>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group-admin">
          <label>Harga Sewa (Rp) *</label>
          <input type="number" name="harga_sewa" placeholder="Misal: 250000" min="0" required>
        </div>

        <div class="form-group-admin">
          <label>Deskripsi / Kelengkapan</label>
          <textarea name="desk_produk" rows="3" placeholder="Inc : Rok, kamisol, selendang dan hijab"></textarea>
        </div>

        <div class="form-group-admin">
          <label>Status Ketersediaan</label>
          <select name="status_produk">
            <option value="tersedia">Tersedia</option>
            <option value="tidak_tersedia">Tidak Tersedia</option>
          </select>
        </div>

        <div class="form-group-admin">
          <label>Upload Foto Produk</label>
          <input type="file" name="foto_produk" accept="image/*">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
          <button type="button" style="padding: 10px 16px; border: 1px solid #d4c8bd; background: none; border-radius: 8px; cursor: pointer;" onclick="document.getElementById('modal-tambah-produk').style.display='none'">Batal</button>
          <button type="submit" style="padding: 10px 20px; background: #623e2b; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
