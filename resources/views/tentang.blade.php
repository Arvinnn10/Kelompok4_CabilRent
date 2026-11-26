<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang - Cabil.Rent</title>

  <!-- Preconnect & Google Fonts (Non-blocking) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset_versioned('css/tentang.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/nav-search.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
</head>
<body>

  <!-- Navigasi atas -->
  @include('layouts.partials.navbar')

  <!-- Hero tentang -->
  <section class="about-hero">
    <div class="container about-hero-wrapper">
      <div class="about-hero-content">
        <h1 class="about-hero-title">Elegance in Every Detail, Made with Heart.</h1>
        <p class="about-hero-desc">
          cabil.rent hadir agar setiap perempuan dapat merasakan keanggunan dalam balutan kebaya tanpa biaya berlebihan.
        </p>
      </div>

      <!-- Foto utama + foto kecil menumpuk -->
      <div class="about-hero-media">
        <div class="about-photo-main">
          <img src="{{ asset('images/tentang/foto2.png') }}" alt="Kebaya wisuda cabil.rent" loading="lazy" decoding="async">
        </div>
        <div class="about-photo-small">
          <img src="{{ asset('images/tentang/sepatu1.png') }}" alt="Heels cabil.rent" loading="lazy" decoding="async">
        </div>
      </div>
    </div>
  </section>

  <!-- Cerita singkat -->
  <section class="about-story">
    <div class="container">
      <hr class="about-divider">

      <div class="about-story-grid">
        <h2 class="about-story-title">Lebih dari sekadar menyewakan kebaya.</h2>
        <div class="about-story-text">
          <p class="dropcap">
            Bagi kami, cabil.rent bukan sekadar menyewakan kebaya, melainkan menghadirkan pengalaman istimewa bagi setiap perempuan yang mempercayakan momen berharganya kepada kami.
          </p>
          <p>
            Ini adalah wujud mimpi dan kerja keras yang ingin kami tumbuhkan menjadi sesuatu yang bermakna. Kami ingin setiap pelanggan pulang dengan penampilan menawan, perasaan dihargai, dan kenangan yang baik.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Foto momen -->
  <section class="about-moments">
    <div class="container about-moments-grid">
      <figure class="about-moment">
        <div class="about-moment-img">
          <img src="{{ asset('images/tentang/foto4.png') }}" alt="Momen wisuda" loading="lazy" decoding="async">
        </div>
      </figure>
      <figure class="about-moment about-moment-short">
        <div class="about-moment-img">
          <img src="{{ asset('images/tentang/foto6.png') }}" alt="Momen lamaran" loading="lazy" decoding="async">
        </div>
      </figure>
      <figure class="about-moment">
        <div class="about-moment-img">
          <img src="{{ asset('images/tentang/foto1.png') }}" alt="Momen pesta" loading="lazy" decoding="async">
        </div>
      </figure>
    </div>
  </section>

  <!-- Nilai cabil.rent -->
  <section class="about-values">
    <div class="container">
      <ul class="values-list">
        <li class="values-item">
          <span class="values-title">Setiap kebaya memiliki cerita</span>
          <span class="values-desc">Dipilih dan dirawat dengan sepenuh hati.</span>
        </li>
        <li class="values-item">
          <span class="values-title">Setiap pelanggan memiliki arti</span>
          <span class="values-desc">Kamu bagian dari perjalanan cabil.rent.</span>
        </li>
        <li class="values-item">
          <span class="values-title">Setiap momen layak dirayakan</span>
          <span class="values-desc">Dengan sepenuh hati, dari awal sampai akhir.</span>
        </li>
      </ul>
    </div>
  </section>

  <!-- Penutup -->
  <section class="about-closing">
    <div class="container about-closing-wrapper">
      <h2 class="about-closing-title">Terima kasih telah menjadi bagian<br>dari perjalanan cabil.rent. &#129293;</h2>
      <a href="{{ url('/koleksi') }}" class="btn-about">LIHAT KOLEKSI</a>
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
            <li><a href="{{ url('/koleksi') }}">Paket Kebaya</a></li>
            <li><a href="{{ url('/koleksi') }}">Heels</a></li>
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

  <script src="{{ asset_versioned('js/cart.js') }}"></script>
  <script src="{{ asset_versioned('js/nav-search.js') }}"></script>
  <script src="{{ asset_versioned('js/transisi.js') }}"></script>
</body>
</html>
