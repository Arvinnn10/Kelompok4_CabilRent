<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Koleksi - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset_versioned('css/koleksi.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/nav-search.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
</head>
<body>

  <!-- Navigasi atas (sama dengan halaman home) -->
  <header class="navbar">
    <div class="container nav-container">
      <!-- Logo cabil.rent -->
      <a href="{{ url('/home') }}" class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img">
        <span>CABIL.RENT</span>
      </a>

      <!-- Menu tengah -->
      <ul class="nav-menu">
        <li><a href="{{ url('/home') }}" class="nav-link">Beranda</a></li>
        <li><a href="{{ url('/koleksi') }}" class="nav-link active">Koleksi</a></li>
        <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang</a></li>
        <li><a href="{{ url('/kontak') }}" class="nav-link">Kontak</a></li>
      </ul>

      <!-- Bagian search dan tombol kanan -->
      <div class="nav-actions">
        <form class="nav-search" action="{{ url('/koleksi') }}" method="GET">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari koleksi">
        </form>

        <a href="{{ url('/keranjang') }}" class="nav-icon-btn" title="Keranjang">
          <span class="cart-badge" id="cart-badge" hidden></span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 10h16l-2 10H6L4 10z"/>
            <path d="M4 10l5-6"/>
            <path d="M20 10l-5-6"/>
            <line x1="9" y1="14" x2="9" y2="17"/>
            <line x1="12" y1="14" x2="12" y2="17"/>
            <line x1="15" y1="14" x2="15" y2="17"/>
          </svg>
        </a>

        @if (Auth::check())
          <a href="{{ route('logout') }}" class="nav-icon-btn" title="Keluar / Logout ({{ Auth::user()->nama_lengkap ?? Auth::user()->username }})" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="9"/>
              <circle cx="12" cy="10" r="3"/>
              <path d="M6.168 18.849a6 6 0 0 1 11.664 0"/>
            </svg>
          </a>
        @else
          <a href="{{ route('login') }}" class="nav-icon-btn" title="Masuk / Login">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="9"/>
              <circle cx="12" cy="10" r="3"/>
              <path d="M6.168 18.849a6 6 0 0 1 11.664 0"/>
            </svg>
          </a>
        @endif
      </div>
    </div>
  </header>

  <!-- Judul halaman -->
  <section class="page-intro">
    <div class="container">
      <h1 class="page-title">Temukan Busana untuk Setiap Momen.</h1>
      <p class="page-desc">
        Kebaya, heels, dan kamisol pilihan yang siap disewa. Pilih kategori, cek ketersediaan, lalu pesan lewat WhatsApp.
      </p>
    </div>
  </section>

  <!-- Banner promo -->
  <section class="promo-section">
    <div class="container">
      <div class="promo-banner">
        <div class="promo-content">
          <h2 class="promo-title">Koleksi Terbaru 2026 sudah tersedia</h2>
          <p class="promo-desc">
            Set kebaya, kain, dan heels yang dipadukan khusus untuk hari wisuda. Sewa satu paket, lebih hemat.
          </p>
          <a href="#semua-koleksi" class="btn-promo">Lihat paket</a>
        </div>
        <div class="promo-image">
          <img src="{{ asset('images/koleksi/banner.png') }}" alt="Koleksi Terbaru 2026">
        </div>
      </div>
    </div>
  </section>

  <!-- Semua koleksi -->
  <section class="katalog-section" id="semua-koleksi">
    <div class="container">
      <div class="katalog-header">
        <h2 class="katalog-title" id="katalog-title">Semua Koleksi</h2>
        <span class="katalog-count" id="katalog-count">{{ $totalProducts ?? count($products) }} produk</span>
      </div>

      <!-- Filter kategori -->
      <div class="katalog-toolbar">
        <div class="filter-chips">
          <a href="{{ url('/koleksi') }}" class="chip {{ empty($kategoriParam) || $kategoriParam === 'semua' ? 'active' : '' }}" data-filter="semua">Semua</a>
          @foreach ($categories as $cat)
            @php $catSlug = $cat->slug ?: \Illuminate\Support\Str::slug($cat->nama_kategori); @endphp
            <a href="{{ url('/koleksi') }}?kategori={{ $catSlug }}" class="chip {{ ($kategoriParam ?? '') === $catSlug ? 'active' : '' }}" data-filter="{{ $catSlug }}">{{ str_ireplace('kemben', 'Kamisol', $cat->nama_kategori) }}</a>
          @endforeach
        </div>
      </div>

      <!-- Grid produk -->
      <div class="produk-grid">
        @forelse ($products as $prod)
          @php
            $prodId = $prod->slug ?: $prod->id;
            $foto = $prod->foto_produk ?: 'koleksi/kebaya-coco.png';
            if (!str_starts_with($foto, 'http')) {
                $foto = asset('images/' . (str_starts_with($foto, 'koleksi/') || file_exists(public_path('images/' . $foto)) ? '' : 'koleksi/') . $foto);
            }
            $catSlug = $prod->category ? ($prod->category->slug ?: \Illuminate\Support\Str::slug($prod->category->nama_kategori)) : 'kebaya';
            $catName = $prod->category ? str_ireplace('kemben', 'Kamisol', $prod->category->nama_kategori) : 'Busana';
            $isHeels = stripos($catName, 'heels') !== false || stripos($catSlug, 'heels') !== false;
          @endphp
          <div class="produk-card" data-kategori="{{ $catSlug }}" data-id="{{ $prodId }}">
            <div class="produk-image">
              <img src="{{ $foto }}" alt="{{ $prod->nama_produk }}">
              <span class="produk-badge">{{ $catName }}</span>
            </div>
            <h3 class="produk-name">{{ $prod->nama_produk }}</h3>
            <p class="produk-size">{{ $prod->desk_produk ?: ($prod->ukuran ? 'Ukuran: ' . $prod->ukuran : 'inc : rok, kamisol, selendang dan hijab') }}</p>
            <div class="produk-footer">
              <span class="produk-price">Rp {{ number_format($prod->harga_sewa, 0, ',', '.') }}{{ $isHeels ? '/3 hari' : '' }}</span>
              <a href="{{ url('/detail') }}?id={{ $prodId }}" class="btn-sewa">Sewa</a>
            </div>
          </div>
        @empty
          <p class="produk-empty">Belum ada produk yang tersedia saat ini.</p>
        @endforelse
      </div>

      <p class="produk-empty" id="produk-empty" hidden>Belum ada produk di kategori ini.</p>
    </div>
  </section>

  <!-- Cara sewa -->
  <section class="langkah-section">
    <div class="container">
      <h2 class="block-title">Cara sewa</h2>
      <div class="langkah-grid">
        <div class="langkah-card">
          <span class="langkah-number">1</span>
          <h3>Pilih busana</h3>
          <p>Telusuri koleksi, lalu catat ukuran dan tanggal acara Anda.</p>
        </div>
        <div class="langkah-card">
          <span class="langkah-number">2</span>
          <h3>Pesan lewat WhatsApp</h3>
          <p>Kirim nama produk dan tanggal. Kami cek ketersediaan dan konfirmasi.</p>
        </div>
        <div class="langkah-card">
          <span class="langkah-number">3</span>
          <h3>Ambil dan kembalikan</h3>
          <p>Ambil di toko atau kirim ke alamat Anda. Kembalikan sesuai tanggal sewa.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Galeri momen -->
  <section class="momen-section">
    <div class="container">
      <div class="momen-header">
        <h2 class="block-title">Galeri momen</h2>
      </div>

      <div class="momen-grid">
        <div class="momen-card momen-tall">
          <img src="{{ asset('images/koleksi/foto2.png') }}" alt="Brokat Maroon">
          <div class="momen-caption">
            <span class="momen-tag">Wisuda</span>
            <span class="momen-name">Brokat Maroon</span>
          </div>
        </div>

        <div class="momen-stack">
          <div class="momen-card">
            <img src="{{ asset('images/koleksi/foto3.png') }}" alt="Tulle Sage Green">
            <div class="momen-caption">
              <span class="momen-tag">Pesta</span>
              <span class="momen-name">Tulle Sage Green</span>
            </div>
          </div>
          <div class="momen-card">
            <img src="{{ asset('images/koleksi/foto4.png') }}" alt="Modern Navy Lane">
            <div class="momen-caption">
              <span class="momen-tag">Wisuda</span>
              <span class="momen-name">Modern Navy Lane</span>
            </div>
          </div>
        </div>

        <div class="momen-card momen-tall">
          <img src="{{ asset('images/koleksi/foto5.png') }}" alt="Kebaya Putih Akad">
          <div class="momen-caption">
            <span class="momen-tag">Lamaran</span>
            <span class="momen-name">Kebaya Putih Akad</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimoni -->
  <section class="testimoni-section">
    <div class="container">
      <h2 class="block-title">Kata pelanggan</h2>
      <div class="testimoni-grid">
        <div class="testimoni-card">
          <p class="testimoni-text">Kebayanya bersih, pas di badan, dan datang tepat waktu untuk wisuda saya.</p>
          <span class="testimoni-name">Andra, Palangka Raya</span>
        </div>
        <div class="testimoni-card">
          <p class="testimoni-text">Heels-nya nyaman dipakai seharian. Admin membalas cepat dan sangat membantu.</p>
          <span class="testimoni-name">Justin, Banjarmasin</span>
        </div>
        <div class="testimoni-card">
          <p class="testimoni-text">Sewa satu paket kebaya dan aksesoris jauh lebih hemat daripada beli.</p>
          <span class="testimoni-name">Bude, Sampit</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Ajakan hubungi -->
  <section class="cta-section">
    <div class="container cta-wrapper">
      <h2 class="cta-title">Tidak menemukan yang dicari?</h2>
      <p class="cta-desc">Hubungi kami, tim CABIL.RENT akan bantu carikan pilihan yang sesuai.</p>
      <a href="https://wa.me/6287894302894?text={{ urlencode('Halo cabil.rent, saya belum menemukan busana yang saya cari. Bisa dibantu?') }}" class="btn-cta" target="_blank" rel="noopener">
        <!-- Ganti file ikon di images/koleksi/ (ukuran diatur di CSS) -->
        <img src="{{ asset('images/wa2.png') }}" alt="" class="btn-cta-icon">
        <span>Chat WhatsApp</span>
      </a>
    </div>
  </section>

  <!-- Bagian footer (sama dengan halaman home) -->
  <footer class="footer-section">
    <div class="container">
      <div class="footer-top">
        <!-- Profil brand -->
        <div class="footer-brand">
          <div class="footer-logo-box">
            <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img">
            <span class="footer-brand-title">CABIL.RENT</span>
          </div>
          <p>Sewa kebaya akad, wisuda, pesta, dan adat yang pas di badan, rapi, dan siap pakai.</p>
          <div class="footer-socials">
            <!-- Ganti file ikon di folder images/ (ukuran diatur di CSS) -->
            <a href="https://www.instagram.com/cabil.rent/" class="social-icon" title="Instagram" target="_blank" rel="noopener">
              <img src="{{ asset('images/instagram.png') }}" alt="Instagram">
            </a>
            <a href="https://wa.me/6287894302894" class="social-icon" title="WhatsApp" target="_blank" rel="noopener">
              <img src="{{ asset('images/wa.png') }}" alt="WhatsApp">
            </a>
          </div>
        </div>

        <!-- Menu jelajahi -->
        <div class="footer-col">
          <h4>JELAJAHI</h4>
          <ul>
            <li><a href="{{ url('/home') }}">Beranda</a></li>
            <li><a href="{{ url('/koleksi') }}">Collection</a></li>
            <li><a href="{{ url('/tentang') }}">About</a></li>
            <li><a href="{{ url('/kontak') }}">Contact</a></li>
          </ul>
        </div>

        <!-- Kategori -->
        <div class="footer-col">
          <h4>KATEGORI</h4>
          <ul>
            <li><a href="{{ url('/koleksi') }}?kategori=kebaya">Paket Kebaya</a></li>
            <li><a href="{{ url('/koleksi') }}?kategori=heels">Heels</a></li>
            <li><a href="{{ url('/koleksi') }}?kategori=kamisol">Kamisol</a></li>
          </ul>
        </div>

        <!-- Kontak -->
        <div class="footer-col">
          <h4>KONTAK</h4>
          <ul class="contact-list">
            <li>Jl. Banjarmasin No. 12</li>
            <li>Buka setiap hari, 09.00&ndash;18.00</li>
            <li>WhatsApp 0878-9430-2894</li>
            <li>halo@cabil.rent.id</li>
          </ul>
        </div>
      </div>

      <!-- Copyright dan legalitas -->
      <div class="footer-bottom">
        <p>&copy; 2026 cabil.rent , Semua hak dilindungi.</p>
        <div class="footer-legal">
          <a href="#">Syarat &amp; Ketentuan</a>
          <a href="#">Kebijakan Privasi</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="{{ asset('js/cart.js') }}"></script>
  <script src="{{ asset('js/nav-search.js') }}"></script>
  <script src="{{ asset('js/transisi.js') }}"></script>
  <script src="{{ asset('js/koleksi.js') }}"></script>
</body>
</html>
