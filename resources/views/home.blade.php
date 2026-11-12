<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cabil.Rent</title>

  <!-- Preconnect & Google Fonts (Non-blocking) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>


  <!-- Navigasi atas -->
  <header class="navbar">
    <div class="container nav-container">
      <!-- Logo cabil.rent -->
      <a href="{{ url('/') }}" class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img" decoding="async">
        <span>CABIL.RENT</span>
      </a>

      <!-- Menu tengah -->
      <ul class="nav-menu">
        <li><a href="{{ url('/') }}" class="nav-link active">Home</a></li>
        <li><a href="{{ url('/koleksi') }}" class="nav-link">Koleksi</a></li>
        <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang</a></li>
        <li><a href="{{ url('/kontak') }}" class="nav-link">Contact</a></li>
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

    <!-- Banner utama -->
  <section class="hero-section">
    <div class="container hero-wrapper">
      <div class="hero-content">
        <h1 class="hero-title">Tampil Anggun di Setiap Momen.</h1>
        <p class="hero-desc">
          Temukan kebaya impianmu untuk acara spesial. Pilihan kebaya cantik dengan desain elegan yang siap membuatmu tampil percaya diri.
        </p>
        <div class="hero-buttons">
          <a href="{{ url('/koleksi') }}" class="btn-primary">MULAI SEWA</a>
          <a href="#" class="btn-secondary">WHATSAPP</a>
        </div>
      </div>

      <!-- Foto model sebelah kanan -->
      <div class="hero-image-box">
        <img src="{{ asset('images/hero-model.png') }}" alt="Model Kebaya Anggun Cabil Rent" fetchpriority="high" decoding="async">
      </div>
    </div>
  </section>

  <!-- Bagian koleksi -->
  <section class="collection-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Collection</h2>
        <a href="{{ url('/koleksi') }}" class="section-link">
          Lihat semua &rarr;
        </a>
      </div>

      <div class="collection-grid">
        <!-- Pakaian kebaya -->
        <a href="{{ url('/koleksi') }}?kategori=kebaya" class="collection-card">
          <div class="collection-image">
            <img src="{{ asset('images/kategori-kebaya.png') }}" alt="Pakaian Kebaya" loading="lazy" decoding="async">
          </div>
          <div class="collection-info">
            <span class="collection-name">Pakaian Kebaya</span>
            <span class="collection-count">9 produk</span>
          </div>
        </a>

        <!-- Heels -->
        <a href="{{ url('/koleksi') }}?kategori=heels" class="collection-card">
          <div class="collection-image">
            <img src="{{ asset('images/sepatu1.png') }}" alt="Heels" loading="lazy" decoding="async">
          </div>
          <div class="collection-info">
            <span class="collection-name">Heels</span>
            <span class="collection-count">4 produk</span>
          </div>
        </a>

        <!-- Kamisol -->
        <a href="{{ url('/koleksi') }}?kategori=kamisol" class="collection-card">
          <div class="collection-image">
            <img src="{{ asset('images/kemben.png') }}" alt="Kamisol" loading="lazy" decoding="async">
          </div>
          <div class="collection-info">
            <span class="collection-name">Kamisol</span>
            <span class="collection-count">3 produk</span>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Galeri momen -->
  <section class="galeri-section">
    <div class="container">
      <div class="galeri-header">
        <div class="galeri-title-group">
          <h2>Galeri Momen</h2>
        </div>
      </div>

      <!-- Baris atas -->
      <div class="galeri-row galeri-row-1">
        <div class="galeri-card">
          <img src="{{ asset('images/foto1.png') }}" alt="Galeri Momen 1" loading="lazy" decoding="async">
        </div>
        <div class="galeri-card">
          <img src="{{ asset('images/foto2.png') }}" alt="Galeri Momen 2" loading="lazy" decoding="async">
        </div>
        <div class="galeri-card">
          <img src="{{ asset('images/foto3.png') }}" alt="Galeri Momen 3" loading="lazy" decoding="async">
        </div>
      </div>

      <!-- Baris bawah -->
      <div class="galeri-row galeri-row-2">
        <div class="galeri-card">
          <img src="{{ asset('images/foto4.png') }}" alt="Galeri Momen 4" loading="lazy" decoding="async">
        </div>
        <div class="galeri-card">
          <img src="{{ asset('images/foto5.png') }}" alt="Galeri Momen 5" loading="lazy" decoding="async">
        </div>
        <div class="galeri-card">
          <img src="{{ asset('images/foto6.png') }}" alt="Galeri Momen 6" loading="lazy" decoding="async">
        </div>
      </div>
    </div>
  </section>

  <!-- Produk populer -->
  <section class="populer-section">
    <div class="container">
      <div class="section-header populer-header">
        <h2 class="section-title">Koleksi Populer</h2>
        <a href="{{ url('/koleksi') }}" class="section-link">Lihat semua &rarr;</a>
      </div>

      <div class="populer-grid">
        <!-- Produk 1 -->
        <div class="product-card">
          <div class="product-image-box">
            <img src="{{ asset('images/foto4.png') }}" alt="Tulle Sage Green" loading="lazy" decoding="async">
            <span class="product-badge">Paket Kebaya</span>
          </div>
          <h3 class="product-title">Tulle Sage Green</h3>
          <p class="product-tag">Satu ukuran</p>
          <span class="product-price">Rp 50.000</span>
        </div>

        <!-- Produk 2 -->
        <div class="product-card">
          <div class="product-image-box">
            <img src="{{ asset('images/sepatu1.png') }}" alt="Tulle Sage Green" loading="lazy" decoding="async">
            <span class="product-badge">Heels</span>
          </div>
          <h3 class="product-title">Tulle Sage Green</h3>
          <p class="product-tag">Satu ukuran</p>
          <span class="product-price">Rp 50.000</span>
        </div>

        <!-- Produk 3 -->
        <div class="product-card">
          <div class="product-image-box">
            <img src="{{ asset('images/kemben.png') }}" alt="Tulle Sage Green" loading="lazy" decoding="async">
            <span class="product-badge">Kamisol</span>
          </div>
          <h3 class="product-title">Tulle Sage Green</h3>
          <p class="product-tag">Satu ukuran</p>
          <span class="product-price">Rp 50.000</span>
        </div>
      </div>
    </div>
  </section>


  <!-- Bagian footer -->
    <footer class="footer-section">
    <div class="container">
      <div class="footer-top">
        <!-- Profil brand dengan Logo -->
        <div class="footer-brand">
          <div class="footer-logo-box">
            <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img" loading="lazy" decoding="async">
            <span class="footer-brand-title">CABIL.RENT</span>
          </div>
          <p>Sewa kebaya akad, wisuda, pesta, dan adat yang pas di badan, rapi, dan siap pakai.</p>
          <div class="footer-socials">
            <a href="#" class="social-circle" title="Instagram">
            <img src="{{ asset('images/instagram.png') }}" alt="Instagram" loading="lazy" decoding="async">
            </a>
            <a href="#" class="social-circle" title="WhatsApp">
           <img src="{{ asset('images/wa.png') }}" alt="WhatsApp" loading="lazy" decoding="async">
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
            <li><a href="{{ url('/koleksi') }}">Paket Kebaya</a></li>
            <li><a href="{{ url('/koleksi') }}">Heels</a></li>
            <li><a href="{{ url('/koleksi') }}">Kemben</a></li>
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
