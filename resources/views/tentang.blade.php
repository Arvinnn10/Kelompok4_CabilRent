<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset('css/tentang.css') }}">
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
        <li><a href="{{ url('/home') }}" class="nav-link active">Home</a></li>
         <li><a href="{{ url('/koleksi') }}" class="nav-link">Koleksi</a></li>
        <li><a href="{{ url('/') }}" class="nav-link">Tentang</a></li>
        <li><a href="{{ url('/kontak') }}" class="nav-link">Kontak</a></li>
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
          <img src="{{ asset('images/tentang/foto2.png') }}" alt="Kebaya wisuda cabil.rent">
        </div>
        <div class="about-photo-small">
          <img src="{{ asset('images/tentang/sepatu1.png') }}" alt="Heels cabil.rent">
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
          <img src="{{ asset('images/tentang/foto3.png') }}" alt="Momen wisuda">
        </div>
      </figure>
      <figure class="about-moment about-moment-short">
        <div class="about-moment-img">
          <img src="{{ asset('images/tentang/foto4.png') }}" alt="Momen lamaran">
        </div>
      </figure>
      <figure class="about-moment">
        <div class="about-moment-img">
          <img src="{{ asset('images/tentang/foto5.png') }}" alt="Momen pesta">
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
            <li><a href="{{ url('/koleksi') }}">Collection</a></li>
            <li><a href="{{ url('/tentang') }}">About</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </div>

        <!-- Kategori -->
        <div class="footer-col">
          <h4>KATEGORI</h4>
          <ul>
            <li><a href="{{ url('/koleksi') }}?kategori=kebaya">Paket Kebaya</a></li>
            <li><a href="{{ url('/koleksi') }}?kategori=heels">Heels</a></li>
            <li><a href="{{ url('/koleksi') }}?kategori=kemben">Kemben</a></li>
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

</body>
</html>
