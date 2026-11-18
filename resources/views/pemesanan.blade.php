<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pemesanan - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pemesanan.css') }}">
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
        <li><a href="{{ url('/koleksi') }}" class="nav-link">Koleksi</a></li>
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

        <a href="#" class="nav-icon-btn" title="Akun Saya">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="9"/>
            <circle cx="12" cy="10" r="3"/>
            <path d="M6.168 18.849a6 6 0 0 1 11.664 0"/>
          </svg>
        </a>
      </div>
    </div>
  </header>

  <!-- Form data pemesanan -->
  <section class="order-section">
    <div class="container order-container">
      <h1 class="order-title">Data Pemesanan</h1>

      <div class="order-grid">
        <form class="order-form" id="order-form" novalidate>
          <fieldset class="order-card">
            <legend class="order-legend">Data pemesan</legend>
            <div class="order-row">
              <div class="order-field">
                <label for="nama">Nama lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Nama kamu" autocomplete="name" required>
                <p class="order-error" data-error-for="nama"></p>
              </div>
              <div class="order-field">
                <label for="whatsapp">Nomor WhatsApp</label>
                <input type="tel" id="whatsapp" name="whatsapp" placeholder="08xx-xxxx-xxxx" inputmode="tel" autocomplete="tel" required>
                <p class="order-error" data-error-for="whatsapp"></p>
              </div>
            </div>
          </fieldset>

          <h2 class="order-subtitle">Acara dan pengambilan</h2>

          <div class="order-field order-field-wide">
            <label for="tanggal">Tanggal acara</label>
            <input type="date" id="tanggal" name="tanggal" required>
            <p class="order-error" data-error-for="tanggal"></p>
          </div>

          <div class="order-field order-field-wide">
            <label for="catatan">Catatan (opsional)</label>
            <textarea id="catatan" name="catatan" rows="4" placeholder="Tinggi badan, warna alternatif, dll."></textarea>
          </div>
        </form>

        <!-- Ringkasan pesanan, diisi oleh js/pemesanan.js -->
        <aside class="cart-summary order-summary">
          <h2 class="summary-title">Ringkasan pesanan</h2>

          <ul class="order-items" id="order-items"></ul>

          <div class="order-empty" id="order-empty" hidden>
            <p>Belum ada busana yang dipilih.</p>
            <a href="{{ url('/koleksi') }}" class="btn-cart-primary">Lihat Koleksi</a>
          </div>

          <div class="summary-rows order-rows" id="order-rows">
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

          <button type="submit" form="order-form" class="btn-lanjut btn-kirim" id="btn-kirim"
                  data-wa="6289518062255">Kirim Pesanan lewat Whatsapp</button>

          <p class="summary-note order-note">Pesanan dikirim ke admin lewat WhatsApp, lalu ketersediaan dikonfirmasi.</p>
        </aside>
      </div>
    </div>
  </section>

  <!-- Template satu produk di ringkasan -->
  <template id="order-item-template">
    <li class="order-item">
      <div class="order-item-img"><img src="" alt=""></div>
      <div class="order-item-info">
        <p class="order-item-name"></p>
        <p class="order-item-meta"></p>
        <p class="order-item-price"></p>
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
            <a href="#" class="social-icon" title="Instagram">
              <img src="{{ asset('images/instagram.png') }}" alt="Instagram">
            </a>
            <a href="#" class="social-icon" title="WhatsApp">
              <img src="{{ asset('images/wa.png') }}" alt="WhatsApp">
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

  <script src="{{ asset('js/cart.js') }}"></script>
  <script src="{{ asset('js/pemesanan.js') }}"></script>
</body>
</html>
