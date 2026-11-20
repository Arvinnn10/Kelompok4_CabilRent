<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset('css/keranjang.css') }}">
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
        <li><a href="{{ url('/home') }}" class="nav-link">Beranda</a></li>
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

          <a href="{{ url('/pemesanan') }}" class="btn-lanjut" id="btn-lanjut">Lanjut ke Pemesanan</a>
          <a href="{{ url('/koleksi') }}" class="btn-tambah">Tambah busana lain</a>

          <p class="summary-note">Deposit dikembalikan penuh setelah busana diterima dalam kondisi baik.</p>
        </aside>
      </div>
    </div>
  </section>

  <!-- Modal Formulir Pemesanan Sewa -->
  <div id="checkout-modal" class="checkout-modal-overlay" style="display: none;">
    <div class="checkout-modal-card">
      <div class="checkout-modal-header">
        <h3 class="checkout-modal-title">Formulir Pemesanan Sewa</h3>
        <button type="button" class="checkout-modal-close" id="checkout-modal-close">&times;</button>
      </div>

      <p class="checkout-modal-subtitle">Lengkapi data untuk konfirmasi ketersediaan busana dan fitting ukuran melalui WhatsApp Admin.</p>

      <form id="checkout-form" action="{{ route('order.checkout') }}" method="POST">
        @csrf
        <input type="hidden" name="items" id="checkout-items">

        <div class="checkout-form-group">
          <label for="nama_lengkap">Nama Lengkap *</label>
          <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Contoh: Siti Rahma" value="{{ Auth::user()->nama_lengkap ?? '' }}" required>
        </div>

        <div class="checkout-form-row">
          <div class="checkout-form-group">
            <label for="no_whatsapp">No. WhatsApp *</label>
            <input type="tel" id="no_whatsapp" name="no_whatsapp" placeholder="08xxxxxxxxxx" value="{{ Auth::user()->no_telp ?? '' }}" required>
          </div>
          <div class="checkout-form-group">
            <label for="instagram">Instagram (Opsional)</label>
            <input type="text" id="instagram" name="instagram" placeholder="@username">
          </div>
        </div>

        <div class="checkout-form-row">
          <div class="checkout-form-group">
            <label for="domisili">Domisili / Kota *</label>
            <input type="text" id="domisili" name="domisili" placeholder="Contoh: Banjarmasin" required>
          </div>
          <div class="checkout-form-group">
            <label for="tb_bb">Tinggi / Berat Badan (TB/BB) *</label>
            <input type="text" id="tb_bb" name="tb_bb" placeholder="Contoh: 160cm / 48kg" required>
          </div>
        </div>

        <div class="checkout-form-group">
          <label for="tanggal_acara">Tanggal Acara *</label>
          <input type="date" id="tanggal_acara" name="tanggal_acara" min="{{ date('Y-m-d') }}" required>
        </div>

        <div class="checkout-summary-mini">
          <div>Item Sewa: <strong id="checkout-item-count">0 busana</strong></div>
          <div>Total Estimasi: <strong id="checkout-total-price">Rp 0</strong></div>
        </div>

        <div class="checkout-modal-actions">
          <button type="button" class="btn-modal-cancel" id="btn-modal-cancel">Batal</button>
          <button type="submit" class="btn-modal-submit" id="btn-modal-submit">Konfirmasi &amp; Chat WhatsApp</button>
        </div>
      </form>
    </div>
  </div>

  <style>
  .checkout-modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(39, 26, 19, 0.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .checkout-modal-card {
    background-color: #ffffff;
    border-radius: 20px;
    max-width: 540px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 32px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(98, 62, 43, 0.15);
  }
  .checkout-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
  }
  .checkout-modal-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 24px;
    color: #3d2010;
    font-weight: 700;
  }
  .checkout-modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #8e8077;
    cursor: pointer;
    line-height: 1;
  }
  .checkout-modal-subtitle {
    font-size: 13px;
    color: #7a6b61;
    margin-bottom: 24px;
    line-height: 1.5;
  }
  .checkout-form-group {
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .checkout-form-group label {
    font-size: 12px;
    font-weight: 600;
    color: #3d2010;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .checkout-form-group input {
    padding: 12px 14px;
    border: 1.5px solid #e2d9cf;
    border-radius: 10px;
    font-size: 14px;
    color: #271a13;
    outline: none;
    transition: border-color 0.2s;
  }
  .checkout-form-group input:focus {
    border-color: #623e2b;
  }
  .checkout-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  @media (max-width: 500px) {
    .checkout-form-row {
      grid-template-columns: 1fr;
    }
  }
  .checkout-summary-mini {
    background-color: #f6efe4;
    border-radius: 12px;
    padding: 14px 18px;
    margin: 20px 0;
    font-size: 13px;
    color: #3d2010;
    display: flex;
    justify-content: space-between;
    border: 1px solid rgba(98, 62, 43, 0.12);
  }
  .checkout-modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
  }
  .btn-modal-cancel {
    background: none;
    border: 1.5px solid #d4c8bd;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    color: #5c4b40;
    cursor: pointer;
  }
  .btn-modal-submit {
    background-color: #623e2b;
    color: #ffffff;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
  }
  .btn-modal-submit:hover {
    background-color: #4a2e20;
  }
  </style>

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

  <script src="{{ asset('js/cart.js') }}"></script>
  <script src="{{ asset('js/keranjang.js') }}"></script>
</body>
</html>
