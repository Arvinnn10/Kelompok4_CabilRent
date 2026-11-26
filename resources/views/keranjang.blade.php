<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset_versioned('css/keranjang.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/nav-search.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
</head>
<body>

  <!-- Navigasi atas -->
  @include('layouts.partials.navbar')

  <!-- Keranjang sewa -->
  <section class="cart-section">
    <div class="container cart-container">
      <h1 class="cart-title">Keranjang Sewa</h1>

      <div class="cart-grid">
        <div class="cart-left">
          <!-- Daftar produk, diisi oleh js/keranjang.js dari template di bawah -->
          <ul class="cart-list" id="cart-list"></ul>

          <!-- Tampil saat keranjang kosong -->
          <div class="cart-empty" id="cart-empty" hidden>
            <p>Keranjangmu masih kosong.</p>
            <a href="{{ url('/koleksi') }}" class="btn-cart-primary">Lihat Collection</a>
          </div>
        </div>

        <!-- Ringkasan biaya -->
        <aside class="cart-summary">
          <h2 class="summary-title">Ringkasan</h2>

          <div class="summary-rows" id="summary-rows" hidden>
            <div class="summary-row">
              <span>Subtotal sewa</span>
              <span class="summary-value" id="summary-subtotal">Rp 0</span>
            </div>
            <div class="summary-row">
              <span>Deposit (dikembalikan)</span>
              <span class="summary-value" id="summary-deposit">Rp 0</span>
            </div>
            <div class="summary-row summary-total">
              <span>Total dibayar awal</span>
              <span class="summary-total-value" id="summary-total">Rp 0</span>
            </div>
          </div>

          @guest
            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;margin-bottom:14px;font-size:12.5px;color:#92400e;line-height:1.5;">
              <strong>Perhatian:</strong> Anda belum login. Untuk memesan sewa busana, Anda perlu <a href="{{ route('login') }}" style="color:#b45309;font-weight:700;text-decoration:underline;">Masuk (Login)</a> terlebih dahulu.
            </div>
          @endguest

          <a href="{{ url('/pemesanan') }}" class="btn-lanjut" id="btn-lanjut">Lanjut ke Pemesanan</a>
          <a href="{{ url('/koleksi') }}" class="btn-tambah">Tambah busana lain</a>

          <p class="summary-note">Deposit dikembalikan penuh setelah busana diterima dalam kondisi baik.</p>
        </aside>
      </div>
    </div>
  </section>

  <!-- Template satu produk di keranjang -->
  <template id="cart-item-template">
    <li class="cart-item">
      <div class="cart-item-img">
        <img src="" alt="">
      </div>
      <div class="cart-item-info">
        <h3 class="cart-item-name"></h3>
        <p class="cart-item-meta"></p>
        <p class="cart-item-durasi"></p>
      </div>
      <div class="cart-item-side">
        <span class="cart-item-price"></span>
        <button type="button" class="cart-item-hapus">Hapus</button>
      </div>
    </li>
  </template>

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

  <script src="{{ asset_versioned('js/cart.js') }}"></script>
  <script src="{{ asset_versioned('js/nav-search.js') }}"></script>
  <script src="{{ asset_versioned('js/transisi.js') }}"></script>
  <script src="{{ asset_versioned('js/keranjang.js') }}"></script>
</body>
</html>
