<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak - Cabil.Rent</title>

  <!-- Preconnect & Google Fonts (Non-blocking) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
</head>
<body>

  <!-- Navigasi atas (sama dengan halaman home) -->
  <header class="navbar">
    <div class="container nav-container">
      <!-- Logo cabil.rent -->
      <a href="{{ url('/') }}" class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img" loading="lazy" decoding="async">
        <span>CABIL.RENT</span>
      </a>

      <!-- Menu tengah -->
      <ul class="nav-menu">
        <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
        <li><a href="{{ url('/koleksi') }}" class="nav-link">Koleksi</a></li>
        <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang</a></li>
        <li><a href="{{ url('/kontak') }}" class="nav-link active">Kontak</a></li>
      </ul>
      <!-- Bagian search dan tombol kanan -->
      <div class="nav-actions">
        <form class="nav-search" action="{{ url('/koleksi') }}" method="GET">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" name="q" placeholder="Cari koleksi">
        </form>

        <a href="#" class="nav-icon-btn" title="Keranjang">
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

  <!-- Judul halaman -->
  <section class="kontak-intro">
    <div class="container kontak-container">
      <h1 class="kontak-title">Hubungi kami.</h1>
      <p class="kontak-desc">
        Ceritakan acara dan busana yang kamu inginkan. Kami balas secepatnya lewat WhatsApp.
      </p>
    </div>
  </section>

  <!-- Info kontak & form -->
  <section class="kontak-main">
    <div class="container kontak-container kontak-grid">
      <div class="kontak-info">
        <div class="info-card">
          <span class="info-label">WHATSAPP</span>
          <span class="info-value">0895&ndash;1806&ndash;2255</span>
        </div>
        <div class="info-card">
          <span class="info-label">ALAMAT</span>
          <span class="info-value">Jl. Melati Indah No. 12</span>
        </div>
        <div class="info-card">
          <span class="info-label">JAM BUKA</span>
          <span class="info-value">Setiap hari | 24 Jam, kecuali selasa</span>
        </div>
        <div class="info-card">
          <span class="info-label">SOSIAL MEDIA</span>
          <span class="info-value">@cabil.rent di Instagram dan TikTok</span>
        </div>

        <!-- Ganti dengan <iframe> Google Maps bila sudah ada -->
        <div class="kontak-map">Peta lokasi (contoh)</div>
      </div>

      <!-- Form pesan, dikirim ke WhatsApp oleh js/kontak.js -->
      <form class="kontak-form" id="kontak-form" action="#" method="POST" data-wa="6289518062255">
        <div class="form-group">
          <label for="nama" class="form-label-lg">Nama</label>
          <input type="text" id="nama" name="nama" placeholder="Nama Lengkap" required>
        </div>

        <div class="form-group">
          <label for="busana">Busana yang diminati</label>
          <select id="busana" name="busana">
            <option value="Pakaian Kebaya">Pakaian Kebaya</option>
            <option value="Heels">Heels</option>
            <option value="Kamisol">Kamisol</option>
          </select>
        </div>

        <div class="form-group">
          <label for="tanggal">Tanggal acara</label>
          <input type="date" id="tanggal" name="tanggal">
        </div>

        <div class="form-group">
          <label for="pesan">Pesan</label>
          <textarea id="pesan" name="pesan" placeholder="Ukuran, warna, atau pertanyaan lain"></textarea>
        </div>

        <button type="submit" class="btn-kirim">Kirim lewat WhatsApp</button>
      </form>
    </div>
  </section>

  <!-- Jalur kontak -->
  <section class="kontak-channel">
    <div class="container kontak-container channel-grid">
      <a href="https://wa.me/6289518062255" class="channel-card" target="_blank" rel="noopener">
        <span class="channel-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>
            <path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-1.8-1.8l.8-1-1-2z"/>
          </svg>
        </span>
        <span class="channel-text">
          <span class="channel-title">WhatsApp</span>
          <span class="channel-sub">Balasan tercepat</span>
        </span>
      </a>

      <a href="#" class="channel-card">
        <span class="channel-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="5"/>
            <circle cx="12" cy="12" r="4"/>
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
          </svg>
        </span>
        <span class="channel-text">
          <span class="channel-title">Instagram</span>
          <span class="channel-sub">@cabil.rent</span>
        </span>
      </a>

      <a href="#" class="channel-card">
        <span class="channel-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 9l1.5-5h13L20 9"/>
            <path d="M4 9a2.67 2.67 0 0 0 5.33 0 2.67 2.67 0 0 0 5.34 0A2.67 2.67 0 0 0 20 9"/>
            <path d="M5 11v9h14v-9"/>
            <path d="M10 20v-5h4v5"/>
          </svg>
        </span>
        <span class="channel-text">
          <span class="channel-title">Datang langsung</span>
          <span class="channel-sub">Coba busana di toko</span>
        </span>
      </a>
    </div>
  </section>

  <!-- Ajakan chat -->
  <section class="kontak-cta">
    <div class="container kontak-container">
      <div class="cta-banner">
        <div>
          <h2 class="cta-banner-title">Butuh cepat?</h2>
          <p class="cta-banner-desc">Chat langsung, biasanya kami balas dalam hitungan menit.</p>
        </div>
        <a href="https://wa.me/6289518062255" class="btn-chat" target="_blank" rel="noopener">Chat sekarang</a>
      </div>
    </div>
  </section>

  <!-- Pertanyaan umum -->
  <section class="kontak-faq">
    <div class="container kontak-container">
      <h2 class="faq-title">Sebelum menghubungi</h2>
      <p class="faq-desc">Jawaban singkat untuk pertanyaan yang sering muncul.</p>

      <div class="faq-list">
        <details class="faq-item">
          <summary>Berapa lama balasan dari admin?</summary>
          <p>Biasanya dalam hitungan menit selama jam buka.</p>
        </details>
        <details class="faq-item">
          <summary>Bisa datang langsung untuk mencoba busana?</summary>
          <p>Bisa. Datang ke toko sesuai jam buka untuk mencoba busana sebelum menyewa.</p>
        </details>
      </div>
    </div>
  </section>

  <!-- Bagian footer (sama dengan halaman home) -->
  <footer class="footer-section">
    <div class="container">
      <div class="footer-top">
        <!-- Profil brand -->
        <div class="footer-brand">
          <div class="footer-logo-box">
            <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img" loading="lazy" decoding="async">
            <span class="footer-brand-title">CABIL.RENT</span>
          </div>
          <p>Sewa kebaya akad, wisuda, pesta, dan adat yang pas di badan, rapi, dan siap pakai.</p>
          <div class="footer-socials">
            <!-- Ganti file ikon di folder images/ (ukuran diatur di CSS) -->
            <a href="#" class="social-icon" title="Instagram">
              <img src="{{ asset('images/instagram.png') }}" alt="Instagram" loading="lazy" decoding="async">
            </a>
            <a href="#" class="social-icon" title="WhatsApp">
              <img src="{{ asset('images/wa.png') }}" alt="WhatsApp" loading="lazy" decoding="async">
            </a>
          </div>
        </div>

        <!-- Menu jelajahi -->
        <div class="footer-col">
          <h4>JELAJAHI</h4>
          <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
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
            <li>WhatsApp 0812-0000-0000</li>
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

  <script src="{{ asset('js/kontak.js') }}"></script>
</body>
</html>
