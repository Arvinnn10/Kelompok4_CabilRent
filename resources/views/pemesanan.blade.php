<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Data Pemesanan - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset_versioned('css/keranjang.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/pemesanan.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/nav-search.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
</head>
<body>

  <!-- Navigasi atas -->
  @include('layouts.partials.navbar')

  <!-- Form data pemesanan -->
  <section class="order-section">
    <div class="container order-container">
      <!-- Tombol kembali ke keranjang -->
      <div style="margin-bottom: 20px;">
        <a href="{{ url('/keranjang') }}" class="btn-kembali" id="btn-kembali-keranjang" style="display:inline-flex;align-items:center;gap:8px;color:#623e2b;font-size:14px;font-weight:600;text-decoration:none;padding:8px 16px;background:#ffffff;border:1px solid #ebdcd0;border-radius:10px;transition:all 0.2s ease;box-shadow:0 1px 3px rgba(0,0,0,0.03);">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali ke Keranjang
        </a>
      </div>

      <h1 class="order-title">Data Pemesanan</h1>

      <!-- Status Login Pengguna -->
      @if (Auth::check())
        <div class="order-auth-notice is-logged-in" style="background:#ffffff;border:1.5px solid #d4c1b2;border-radius:14px;padding:16px 20px;margin-bottom:28px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 8px rgba(98,62,43,0.06);">
          <div style="width:12px;height:12px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 3px rgba(34,197,94,0.25);flex-shrink:0;"></div>
          <div style="flex:1;">
            <p style="margin:0 0 4px 0;font-size:14px;color:#33241b;">Status Akun: <strong style="color:#15803d;">Sedang Login</strong> sebagai <strong>{{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</strong> ({{ Auth::user()->no_telp ?: Auth::user()->email }})</p>
            <p style="margin:0;font-size:12px;color:#6b5749;">Data pesanan sewa Anda akan otomatis tercatat dan dapat dipantau di menu <a href="{{ route('riwayat.pesanan') }}" style="color:#623e2b;font-weight:700;text-decoration:underline;">Riwayat Pesanan Saya</a>.</p>
          </div>
        </div>
      @else
        <div class="order-auth-notice is-guest" style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:14px;padding:16px 20px;margin-bottom:28px;display:flex;align-items:center;gap:14px;">
          <div style="width:12px;height:12px;border-radius:50%;background:#ef4444;flex-shrink:0;"></div>
          <div style="flex:1;">
            <p style="margin:0 0 4px 0;font-size:14px;color:#991b1b;font-weight:700;">Perhatian: Anda Belum Login</p>
            <p style="margin:0;font-size:13px;color:#7f1d1d;">Untuk dapat mengirim pesanan sewa busana, Anda wajib masuk terlebih dahulu. Silakan <a href="{{ route('login') }}" style="color:#b91c1c;font-weight:700;text-decoration:underline;">Masuk (Login)</a> atau <a href="{{ route('register') }}" style="color:#b91c1c;font-weight:700;text-decoration:underline;">Daftar Akun Baru</a>.</p>
          </div>
        </div>
      @endif

      <div class="order-grid">
        <form class="order-form" id="order-form" data-checkout="{{ route('order.checkout') }}" novalidate>
          <fieldset class="order-card">
            <legend class="order-legend">Data pemesan</legend>
            <p style="font-size:12px;color:#786558;margin:-8px 0 16px 0;clear:both;">
              <span style="color:#dc2626;font-weight:700;">*</span> Menandakan kolom <strong>wajib diisi</strong>. Kolom bertanda <em>(Opsional)</em> boleh dikosongkan.
            </p>
            <div class="order-row">
              <div class="order-field">
                <label for="nama">Nama lengkap <span class="badge-tag badge-tag-req" style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi)</span></label>
                <input type="text" id="nama" name="nama" value="{{ Auth::user()->nama_lengkap ?? Auth::user()->username ?? '' }}" placeholder="Nama kamu" autocomplete="name" required>
                <p class="order-error" data-error-for="nama"></p>
              </div>
              <div class="order-field">
                <label for="whatsapp">Nomor WhatsApp <span class="badge-tag badge-tag-req" style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi - Angka saja)</span></label>
                <input type="tel" id="whatsapp" name="whatsapp" value="{{ Auth::user()->no_telp ?? '' }}" placeholder="Contoh: 081234567890" inputmode="numeric" pattern="[0-9+\s-]*" autocomplete="tel" maxlength="20" required>
                <p class="order-error" data-error-for="whatsapp"></p>
              </div>
            </div>
            <div class="order-row">
              <div class="order-field">
                <label for="domisili">Domisili / Kota <span class="badge-tag badge-tag-req" style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi)</span></label>
                <input type="text" id="domisili" name="domisili" placeholder="Contoh: Banjarmasin" maxlength="100" required>
                <p class="order-error" data-error-for="domisili"></p>
              </div>
              <div class="order-field">
                <label for="tb_bb">Tinggi / Berat Badan (TB/BB) <span class="badge-tag badge-tag-req" style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi)</span></label>
                <input type="text" id="tb_bb" name="tb_bb" placeholder="Contoh: 160 cm / 50 kg" maxlength="30" required>
                <p class="order-error" data-error-for="tb_bb"></p>
              </div>
            </div>
            <div class="order-field order-field-wide">
              <label for="instagram">Akun Instagram <span class="badge-tag badge-tag-opt" style="color:#78716c;font-size:11px;font-weight:normal;">(Opsional / Tidak wajib)</span></label>
              <input type="text" id="instagram" name="instagram" placeholder="@username">
              <p class="order-error" data-error-for="instagram"></p>
            </div>
          </fieldset>

          <h2 class="order-subtitle">Acara dan pengambilan</h2>

          <div class="order-field order-field-wide">
            <label for="tanggal">Tanggal acara <span class="badge-tag badge-tag-req" style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi)</span></label>
            <input type="date" id="tanggal" name="tanggal" required>
            <p class="order-error" data-error-for="tanggal"></p>
          </div>

          <div class="order-field order-field-wide">
            <label for="catatan">Catatan tambahan <span class="badge-tag badge-tag-opt" style="color:#78716c;font-size:11px;font-weight:normal;">(Opsional / Tidak wajib)</span></label>
            <textarea id="catatan" name="catatan" rows="3" placeholder="Warna alternatif, permintaan khusus hijab, dll."></textarea>
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

          @auth
            <button type="submit" form="order-form" class="btn-lanjut btn-kirim" id="btn-kirim"
                    data-wa="{{ config('services.whatsapp.admin_number', '6287894302894') }}">Kirim Pesanan lewat Whatsapp</button>
          @else
            <a href="{{ route('login') }}" class="btn-lanjut" style="text-align:center;display:block;text-decoration:none;background:#b91c1c;color:#fff;" title="Anda harus login terlebih dahulu">Masuk (Login) untuk Memesan</a>
          @endauth

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

  <!-- Modal Konfirmasi Penyesuaian Stok & Notifikasi Ketersediaan (Serasi CabilRent) -->
  <div class="modal-overlay" id="stock-modal-overlay" hidden>
    <div class="modal" id="stock-modal" role="dialog" aria-modal="true" aria-labelledby="stock-modal-title">
      <h3 class="modal-title" id="stock-modal-title">Penyesuaian Stok</h3>
      <div class="modal-text" id="stock-modal-content"></div>
      <div class="modal-actions" id="stock-modal-actions">
        <button type="button" class="btn-batal" id="btn-stock-batal">Batalkan</button>
        <button type="button" class="btn-ya" id="btn-stock-lanjut">Lanjutkan Checkout</button>
      </div>
    </div>
  </div>

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
  <script src="{{ asset_versioned('js/pemesanan.js') }}"></script>
</body>
</html>
