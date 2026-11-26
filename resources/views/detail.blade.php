<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Produk - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset_versioned('css/detail.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/nav-search.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
</head>
<body>

  <!-- Navigasi atas -->
  @include('layouts.partials.navbar')

  <!-- Tombol kembali ke koleksi -->
  <div class="container" style="padding-top: 24px; padding-bottom: 0;">
    <a href="{{ url('/koleksi') }}" class="btn-kembali" id="btn-kembali-koleksi" style="display:inline-flex;align-items:center;gap:8px;color:#623e2b;font-size:14px;font-weight:600;text-decoration:none;padding:8px 16px;background:#ffffff;border:1px solid #ebdcd0;border-radius:10px;transition:all 0.2s ease;box-shadow:0 1px 3px rgba(0,0,0,0.03);">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
      Kembali ke Koleksi
    </a>
  </div>

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
    <div class="container detail-container detail-grid" id="detail-produk"
         data-id="{{ $prodId }}"
         data-nama="{{ $product->nama_produk }}"
         data-kategori="{{ $categoryName }}"
         data-harga="{{ $product->harga_sewa }}"
         data-keterangan="{{ $product->desk_produk ?: 'Kondisi siap pakai dan diperiksa sebelum dikirim.' }}"
         data-gambar="{{ $fotoPath }}">

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

        <form class="detail-form" id="detail-form"
              data-keranjang="{{ url('/keranjang') }}"
              data-auth="{{ Auth::check() ? '1' : '0' }}"
              data-login="{{ route('login') }}"
              action="{{ route('cart.add') }}" method="POST">
          @csrf
          <input type="hidden" name="produk_id" value="{{ $prodId }}">
          <label for="tanggal-pesanan" class="detail-label">TANGGAL PESANAN</label>
          <input type="date" id="tanggal-pesanan" name="tanggal" min="{{ date('Y-m-d') }}" required>
          <p class="detail-note">Ketersediaan dikonfirmasi admin lewat WhatsApp.</p>

          @guest
            <p style="font-size:12.5px;color:#b45309;background:#fffbeb;border:1px solid #fde68a;padding:8px 12px;border-radius:8px;margin-bottom:12px;">
              <strong>Info:</strong> Anda perlu <a href="{{ route('login') }}" style="color:#b45309;font-weight:700;text-decoration:underline;">Masuk (Login)</a> terlebih dahulu untuk menambahkan ke keranjang.
            </p>
          @endguest

          <div class="detail-buttons">
            <button type="button" class="btn-sewa-sekarang" id="btn-beli-wa">Sewa Langsung via WhatsApp</button>
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
  <script src="{{ asset_versioned('js/cart.js') }}"></script>
  <script src="{{ asset_versioned('js/nav-search.js') }}"></script>
  <script src="{{ asset_versioned('js/transisi.js') }}"></script>
  <script src="{{ asset_versioned('js/detail.js') }}"></script>
</body>
</html>
