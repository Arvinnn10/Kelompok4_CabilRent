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

  <link rel="stylesheet" href="{{ asset_versioned('css/kontak.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/nav-search.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
</head>
<body>

  <!-- Navigasi atas -->
  @include('layouts.partials.navbar')

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
          <span class="info-value">0878&ndash;9430&ndash;2894</span>
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

        <!-- Foto toko cabil.rent -->
        <div class="kontak-map">
          <img src="{{ asset('images/kontak/toko.png') }}" alt="Toko cabil.rent" loading="lazy" decoding="async">
        </div>
      </div>

      <!-- Form pesan, dikirim ke WhatsApp oleh js/kontak.js -->
      <form class="kontak-form" id="kontak-form" action="#" method="POST" data-wa="6287894302894">
        @csrf
        <!-- Header formulir pertanyaan & konsultasi yang natural -->
        <div class="kontak-form-header" style="margin-bottom: 22px;">
          <h2 style="font-size: 20px; font-weight: 700; color: #271a13; margin-bottom: 6px;">Tanya &amp; Konsultasi Busana</h2>
          <p style="font-size: 13.5px; color: #6e5e54; line-height: 1.5; margin: 0 0 10px 0;">
            Punya pertanyaan seputar ukuran, ketersediaan model, atau ingin fitting dulu? Silakan kirim pesan ke admin kami di bawah ini.
          </p>
          <div style="font-size: 12.5px; color: #8c6a54; background: #faf4ef; padding: 8px 12px; border-radius: 8px; border: 1px solid #ebdcd0; display: inline-flex; align-items: center; gap: 6px;">
            <span>💡 Ingin langsung menyewa? Pilih busana langsung di katalog <a href="{{ url('/koleksi') }}" style="color: #623e2b; font-weight: 700; text-decoration: underline;">Koleksi</a> kami.</span>
          </div>
        </div>

        <div class="form-group">
          <label for="nama" class="form-label-lg">Nama</label>
          <input type="text" id="nama" name="nama" placeholder="Nama Lengkap" required>
        </div>

        <div class="form-group">
          <label for="busana">Busana yang ditanyakan</label>
          <select id="busana" name="busana">
            <option value="Pakaian Kebaya">Pakaian Kebaya</option>
            <option value="Heels">Heels</option>
            <option value="Kamisol">Kamisol</option>
          </select>
        </div>

        <div class="form-group">
          <label for="tanggal">Rencana tanggal acara (opsional)</label>
          <input type="date" id="tanggal" name="tanggal">
        </div>

        <div class="form-group">
          <label for="pesan">Isi Pertanyaan</label>
          <textarea id="pesan" name="pesan" placeholder="Tuliskan pertanyaan seputar ukuran, ketersediaan warna, atau info lainnya"></textarea>
        </div>

        <button type="submit" class="btn-kirim">Kirim Pertanyaan via WhatsApp</button>
      </form>
    </div>
  </section>

  <!-- Jalur kontak -->
  <section class="kontak-channel">
    <div class="container kontak-container channel-grid">
      <a href="https://wa.me/6287894302894" class="channel-card" target="_blank" rel="noopener">
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

      <a href="https://www.instagram.com/cabil.rent/" class="channel-card" target="_blank" rel="noopener">
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

      <a href="https://www.google.com/maps/search/?api=1&query=Antaran+Besar%2C+Kota+Banjarmasin%2C+Kalimantan+Selatan" class="channel-card" target="_blank" rel="noopener">
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
        <a href="https://wa.me/6287894302894" class="btn-chat" target="_blank" rel="noopener">Chat sekarang</a>
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
            <a href="https://www.instagram.com/cabil.rent/" class="social-icon" title="Instagram" target="_blank" rel="noopener">
              <img src="{{ asset('images/instagram.png') }}" alt="Instagram" loading="lazy" decoding="async">
            </a>
            <a href="https://wa.me/6287894302894" class="social-icon" title="WhatsApp" target="_blank" rel="noopener">
              <img src="{{ asset('images/wa.png') }}" alt="WhatsApp" loading="lazy" decoding="async">
            </a>
          </div>
        </div>

        <!-- Menu jelajahi -->
        <div class="footer-col">
          <h4>JELAJAHI</h4>
          <ul>
            <li><a href="{{ url('/') }}">Beranda</a></li>
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

  <script src="{{ asset_versioned('js/kontak.js') }}"></script>
  <script src="{{ asset_versioned('js/cart.js') }}"></script>
  <script src="{{ asset_versioned('js/nav-search.js') }}"></script>
  <script src="{{ asset_versioned('js/transisi.js') }}"></script>
</body>
</html>
