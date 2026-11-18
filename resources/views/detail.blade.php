<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Produk - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
</head>
<body>

  <!-- Navigasi atas -->
  <header class="navbar">
    <div class="container nav-container">
      <!-- Logo cabil.rent -->
      <a href="{{ url('/home') }}" class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img">
        <span>CABIL.RENT</span>
      </a>

      <!-- Menu tengah -->
      <ul class="nav-menu">
        <li><a href="{{ url('/home') }}" class="nav-link">Home</a></li>
        <li><a href="{{ url('/koleksi') }}" class="nav-link active">Koleksi</a></li>
        <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang</a></li>
        <li><a href="{{ url('/kontak') }}" class="nav-link">Kontak</a></li>
      </ul>

      <!-- Bagian search dan tombol kanan -->
      <div class="nav-actions">
        <div class="nav-search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" placeholder="Cari koleksi">
        </div>

        <a href="{{ url('/keranjang') }}" class="nav-icon-btn" title="Keranjang">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 10h16l-2 10H6L4 10z"/>
            <path d="M4 10l5-6"/>
            <path d="M20 10l-5-6"/>
            <line x1="9" y1="14" x2="9" y2="17"/>
            <line x1="12" y1="14" x2="12" y2="17"/>
            <line x1="15" y1="14" x2="15" y2="17"/>
          </svg>
        </a>

        <a href="{{ Auth::check() ? route('logout') : route('login') }}" class="nav-icon-btn" title="{{ Auth::check() ? 'Keluar / Logout (' . (Auth::user()->nama_lengkap ?? Auth::user()->username) . ')' : 'Masuk / Login' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="9"/>
            <circle cx="12" cy="10" r="3"/>
            <path d="M6.168 18.849a6 6 0 0 1 11.664 0"/>
          </svg>
        </a>
      </div>
    </div>
  </header>

  @php
    $prodId = $product->slug ?: $product->id;
    $fotoPath = $product->foto_produk;
    if (!str_starts_with($fotoPath, 'http')) {
        $fotoPath = asset('images/' . (str_starts_with($fotoPath, 'koleksi/') ? $fotoPath : 'koleksi/' . $fotoPath));
    }
    $categoryName = $product->category->nama_kategori ?? 'Busana';
  @endphp

  <!-- Detail produk -->
  <section class="detail-section">
<<<<<<< HEAD
    <div class="container detail-container detail-grid" id="detail-produk"
         data-id="{{ $prodId }}"
         data-nama="{{ $product->nama_produk }}"
         data-kategori="{{ $categoryName }}"
         data-harga="{{ $product->harga_sewa }}"
         data-keterangan="{{ $product->desk_produk ?: 'Kondisi siap pakai dan diperiksa sebelum dikirim.' }}"
         data-gambar="{{ $fotoPath }}">
=======
    <div class="container detail-container detail-grid" id="detail-produk" style="visibility:hidden"
         data-id="kebaya-coco"
         data-nama="Coco Kebaya"
         data-kategori="Pakaian Kebaya"
         data-harga="200000"
         data-keterangan="Inc : Rok, kamisol, selendang dan hijab."
         data-gambar="{{ asset('images/koleksi/kebaya-coco.png') }}">
>>>>>>> d0dfb69 (halaman pemesanan dan perbaikan halaman koleksi agar bisa masuk ke halaman whatsapp otomatis UPDATE V2)

      <div class="detail-image">
        <img src="{{ $fotoPath }}" alt="{{ $product->nama_produk }}" id="detail-gambar">
      </div>

      <div class="detail-info">
        <span class="detail-kategori" id="detail-kategori">{{ strtoupper($categoryName) }}</span>
        <h1 class="detail-nama" id="detail-nama">{{ $product->nama_produk }}</h1>
        <p class="detail-harga">
          <span id="detail-harga">Rp {{ number_format($product->harga_sewa, 0, ',', '.') }}</span>
          <small>/ 3 hari sewa</small>
        </p>
        <p class="detail-keterangan" id="detail-keterangan">{{ $product->desk_produk ?: 'Inc : Rok, kamisol, selendang dan hijab.' }}</p>

        <form class="detail-form" id="detail-form" data-keranjang="{{ url('/keranjang') }}" action="{{ route('cart.add') }}" method="POST">
          @csrf
          <input type="hidden" name="produk_id" value="{{ $prodId }}">
          <label for="tanggal-pesanan" class="detail-label">TANGGAL PESANAN</label>
          <input type="date" id="tanggal-pesanan" name="tanggal" min="{{ date('Y-m-d') }}" required>
          <p class="detail-note">Ketersediaan dikonfirmasi admin lewat WhatsApp.</p>

          <div class="detail-buttons">
            <button type="submit" name="aksi" value="sewa" class="btn-sewa-sekarang">Sewa Sekarang</button>
            <button type="submit" name="aksi" value="tambah" class="btn-tambah-keranjang" id="btn-tambah-keranjang">Tambah ke Keranjang</button>
          </div>
        </form>

        <a href="https://wa.me/6287894302894?text={{ urlencode('Halo cabil.rent, saya ingin tanya ketersediaan sewa untuk busana ' . $product->nama_produk . '.') }}"
           class="btn-tanya" id="btn-tanya" data-wa="6287894302894" target="_blank" rel="noopener">Tanya via WhatsApp</a>

        <div class="detail-accordion">
          <details class="detail-item" open>
            <summary>Detail busana</summary>
            <p>Dicuci dan diperiksa sebelum disewakan. Kondisi dipastikan siap pakai.</p>
          </details>
          <details class="detail-item">
            <summary>Ketentuan sewa</summary>
            <p>Sewa berlaku 3 hari. Deposit dikembalikan penuh setelah busana diterima dalam kondisi baik.</p>
          </details>
          <details class="detail-item">
            <summary>Perawatan</summary>
            <p>Tidak perlu dicuci sendiri. Busana dirawat oleh tim kami setelah dikembalikan.</p>
          </details>
        </div>
      </div>
    </div>
  </section>

  <!-- Bagian footer -->
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
            <a href="#" class="social-icon" title="Instagram">
              <img src="{{ asset('images/icon-instagram.png') }}" alt="Instagram">
            </a>
            <a href="#" class="social-icon" title="WhatsApp">
              <img src="{{ asset('images/icon-whatsapp.png') }}" alt="WhatsApp">
            </a>
          </div>
        </div>

        <!-- Menu jelajahi -->
        <div class="footer-col">
          <h4>JELAJAHI</h4>
          <ul>
            <li><a href="{{ url('/home') }}">Home</a></li>
            <li><a href="{{ url('/koleksi') }}">Koleksi</a></li>
            <li><a href="{{ url('/tentang') }}">Tentang</a></li>
            <li><a href="{{ url('/kontak') }}">Kontak</a></li>
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
            <li>Antaran Besar, Kota Banjarmasin, Kalimantan Selatan</li>
            <li>Rabu - Senin | Buka 24 Jam</li>
            <li>Selasa: Tutup</li>
            <li>WhatsApp 0878-9430-2894</li>
          </ul>
        </div>
      </div>

      <!-- Copyright dan legalitas -->
      <div class="footer-bottom">
        <p>&copy; 2026 cabil.rent. All Rights Reserved</p>
        <div class="footer-legal">
          <a href="#">Syarat &amp; Ketentuan</a>
          <a href="#">Kebijakan Privasi</a>
        </div>
      </div>
    </div>
  </footer>

  <noscript><style>#detail-produk { visibility: visible !important; }</style></noscript>
  <script src="{{ asset('js/cart.js') }}"></script>
  <script src="{{ asset('js/detail.js') }}"></script>
</body>
</html>
