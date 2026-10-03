<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cabil.Rent - Persewaan Kebaya Anggun</title>
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

  <!-- Navigasi atas -->
  <header class="navbar">
    <div class="container nav-container">
      <!-- Logo cabil.rent -->
      <a href="#" class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="cabil.rent logo" class="brand-logo-img">
        <span>CABIL.RENT</span>
      </a>

      <!-- Menu tengah -->
      <ul class="nav-menu">
        <li><a href="#" class="nav-link active">Home</a></li>
        <li><a href="#" class="nav-link">Collection</a></li>
        <li><a href="#" class="nav-link">About</a></li>
        <li><a href="#" class="nav-link">Contact</a></li>
      </ul>

      <!-- Bagian search dan tombol kanan -->
      <div class="nav-actions">
        <div class="nav-search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" placeholder="Search">
        </div>

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

  <!-- Banner utama -->
  <section class="hero-section">
    <div class="container hero-wrapper">
      <div class="hero-content">
        <h1 class="hero-title">Tampil Anggun di Setiap Momen.</h1>
        <p class="hero-desc">
          Temukan kebaya impianmu untuk acara spesial. Pilihan kebaya cantik dengan desain elegan yang siap membuatmu tampil percaya diri.
        </p>
        <div class="hero-buttons">
          <a href="#" class="btn-primary">MULAI SEWA</a>
          <a href="#" class="btn-secondary">MULAI SEWA</a>
        </div>
      </div>

      <!-- Foto model sebelah kanan -->
      <div class="hero-image-box">
        <img src="{{ asset('images/hero-model.png') }}" alt="Model Kebaya Anggun Cabil Rent">
      </div>
    </div>
  </section>

  <!-- Bagian koleksi -->
  <section class="collection-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Collection</h2>
        <a href="#" class="section-link">
          Lihat semua &rarr;
        </a>
      </div>

      <div class="collection-grid">
        <!-- Paket kebaya -->
        <div class="collection-card">
          <img src="{{ asset('images/produk-rose-dusty.png') }}" alt="Paket Kebaya">
          <div class="card-badge">
            <span class="badge-title">Paket Kebaya</span>
            <span class="badge-count">78 produk &rarr;</span>
          </div>
        </div>

        <!-- Heels -->
        <div class="collection-card">
          <img src="{{ asset('images/kategori-heels1.png') }}" alt="Heels">
          <div class="card-badge">
            <span class="badge-title">Heels</span>
            <span class="badge-count">62 produk &rarr;</span>
          </div>
        </div>

        <!-- Kemben -->
        <div class="collection-card">
          <img src="{{ asset('images/kemben.png') }}" alt="Kemben">
          <div class="card-badge">
            <span class="badge-title">Kemben</span>
            <span class="badge-count">44 produk &rarr;</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Galeri momen -->
  <section class="galeri-section">
    <div class="container">
      <div class="galeri-header">
        <div class="galeri-title-group">
          <h2>Galeri Momen</h2>
          <p>Pelanggan kami di hari-hari spesialnya</p>
        </div>
        <a href="#" class="galeri-link">Lihat lebih banyak &rarr;</a>
      </div>

      <!-- Baris atas -->
      <div class="galeri-row galeri-row-1">
        <div class="galeri-card">
          <img src="{{ asset('images/foto1.png') }}" alt="Galeri Momen 1">
        </div>
        <div class="galeri-card">
          <img src="{{ asset('images/foto2.png') }}" alt="Galeri Momen 2">
        </div>
        <div class="galeri-card">
          <img src="{{ asset('images/foto3.png') }}" alt="Galeri Momen 3">
        </div>
      </div>

      <!-- Baris bawah -->
      <div class="galeri-row galeri-row-2">
        <div class="galeri-card">
          <img src="{{ asset('images/foto4.png') }}" alt="Galeri Momen 4">
        </div>
        <div class="galeri-card">
          <img src="{{ asset('images/foto5.png') }}" alt="Galeri Momen 5">
        </div>
        <div class="galeri-card">
          <img src="{{ asset('images/foto6.png') }}" alt="Galeri Momen 6">
        </div>
      </div>
    </div>
  </section>

  <!-- Produk populer -->
  <section class="populer-section">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Koleksi Populer</h2>
        <a href="#" class="section-link">Lihat semua &rarr;</a>
      </div>

      <div class="populer-grid">
        <!-- Produk 1 -->
        <div class="product-card">
          <div class="product-image-box">
            <img src="{{ asset('images/sepatu1.png') }}" alt="Brokat Maroon">
          </div>
          <h3 class="product-title">Brokat Maroon</h3>
          <p class="product-tag">Akad &bull; S M L XL</p>
          <div class="product-footer">
            <span class="product-price">Rp 250.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <!-- Produk 2 -->
        <div class="product-card">
          <div class="product-image-box">
            <img src="{{ asset('images/sepatu2.png') }}" alt="Tulle Sage Green">
          </div>
          <h3 class="product-title">Tulle Sage Green</h3>
          <p class="product-tag">Wisuda &bull; S M L</p>
          <div class="product-footer">
            <span class="product-price">Rp 200.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <!-- Produk 3 -->
        <div class="product-card">
          <div class="product-image-box">
            <img src="{{ asset('images/bajuhitam.png') }}" alt="Encim Gold Klasik">
          </div>
          <h3 class="product-title">Encim Gold Klasik</h3>
          <p class="product-tag">Adat &bull; M L XL</p>
          <div class="product-footer">
            <span class="product-price">Rp 230.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <!-- Produk 4 -->
        <div class="product-card">
          <div class="product-image-box">
            <img src="{{ asset('images/bajuputih.png') }}" alt="Modern Navy Lace">
          </div>
          <h3 class="product-title">Modern Navy Lace</h3>
          <p class="product-tag">Pesta &bull; S M L</p>
          <div class="product-footer">
            <span class="product-price">Rp 240.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
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
          <h3>Kebaya Anggun</h3>
          <p>Sewa kebaya akad, wisuda, pesta, dan adat yang pas di badan, rapi, dan siap pakai.</p>
          <div class="footer-socials">
            <a href="#" class="social-circle" title="Instagram">IG</a>
            <a href="#" class="social-circle" title="WhatsApp">WA</a>
            <a href="#" class="social-circle" title="TikTok">TT</a>
          </div>
        </div>

        <!-- Menu jelajahi -->
        <div class="footer-col">
          <h4>JELAJAHI</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Collection</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Cara Menyewa</a></li>
            <li><a href="#">FAQ</a></li>
          </ul>
        </div>

        <!-- Kategori -->
        <div class="footer-col">
          <h4>KATEGORI</h4>
          <ul>
            <li><a href="#">Paket Kebaya</a></li>
            <li><a href="#">Heels</a></li>
            <li><a href="#">Kemben</a></li>
          </ul>
        </div>

        <!-- Kontak -->
        <div class="footer-col">
          <h4>KONTAK</h4>
          <ul class="contact-list">
            <li>Jl. Cantik No. 12</li>
            <li>Buka setiap hari, 09.00&ndash;18.00</li>
            <li>WhatsApp: 0812-0000-0000</li>
            <li>halo@cabil.rent.id</li>
          </ul>
        </div>
      </div>

      <!-- Copyright dan legalitas -->
      <div class="footer-bottom">
        <p>&copy; 2026 Kebaya Anggun. Semua hak dilindungi.</p>
        <div class="footer-legal">
          <a href="#">Syarat &amp; Ketentuan</a>
          <a href="#">Kebijakan Privasi</a>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
