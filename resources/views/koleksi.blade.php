<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Koleksi - Cabil.Rent</title>
  <link rel="stylesheet" href="{{ asset('css/koleksi.css') }}">
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
        <li><a href="{{ url('/home') }}" class="nav-link">Home</a></li>
        <li><a href="{{ url('/koleksi') }}" class="nav-link active">Collection</a></li>
        <li><a href="#" class="nav-link">About</a></li>
        <li><a href="#" class="nav-link">Contact</a></li>
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

  <!-- Judul halaman -->
  <section class="page-intro">
    <div class="container">
      <h1 class="page-title">Temukan Busana untuk Setiap Momen.</h1>
      <p class="page-desc">
        Kebaya, heels, dan Kemben pilihan yang siap disewa. Pilih kategori, cek ketersediaan, lalu pesan lewat WhatsApp.
      </p>
    </div>
  </section>

  <!-- Banner promo -->
  <section class="promo-section">
    <div class="container">
      <div class="promo-banner">
        <div class="promo-content">
          <h2 class="promo-title">Koleksi Terbaru 2026 sudah tersedia</h2>
          <p class="promo-desc">
            Set kebaya, kain, dan heels yang dipadukan khusus untuk hari wisuda. Sewa satu paket, lebih hemat.
          </p>
          <a href="#" class="btn-promo">Lihat paket</a>
        </div>
        <div class="promo-image">
          <img src="{{ asset('images/foto1.png') }}" alt="Koleksi Terbaru 2026">
        </div>
      </div>
    </div>
  </section>

  <!-- Kategori -->
  <section class="kategori-section">
    <div class="container">
      <div class="kategori-grid">
        <a href="{{ url('/koleksi') }}?kategori=kebaya" class="kategori-card">
          <div class="kategori-image">
            <img src="{{ asset('images/kategori-kebaya.png') }}" alt="Pakaian Kebaya">
          </div>
          <div class="kategori-info">
            <span class="kategori-name">Pakaian Kebaya</span>
            <span class="kategori-count">8 produk</span>
          </div>
        </a>

        <a href="{{ url('/koleksi') }}?kategori=heels" class="kategori-card">
          <div class="kategori-image">
            <img src="{{ asset('images/sepatu1.png') }}" alt="Heels">
          </div>
          <div class="kategori-info">
            <span class="kategori-name">Heels</span>
            <span class="kategori-count">3 produk</span>
          </div>
        </a>

        <a href="{{ url('/koleksi') }}?kategori=kemben" class="kategori-card">
          <div class="kategori-image">
            <img src="{{ asset('images/kemben.png') }}" alt="Kemben">
          </div>
          <div class="kategori-info">
            <span class="kategori-name">Kemben</span>
            <span class="kategori-count">5 produk</span>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Semua koleksi -->
  <section class="katalog-section">
    <div class="container">
      <div class="katalog-header">
        <h2 class="katalog-title">Semua Koleksi</h2>
        <span class="katalog-count">16 produk</span>
      </div>

      <!-- Filter kategori & urutan (dikirim sebagai query string ?kategori=...&urut=...) -->
      <div class="katalog-toolbar">
        <div class="filter-chips">
          <a href="{{ url('/koleksi') }}" class="chip active">Semua</a>
          <a href="{{ url('/koleksi') }}?kategori=kebaya" class="chip">Pakaian Kebaya</a>
          <a href="{{ url('/koleksi') }}?kategori=heels" class="chip">Heels</a>
          <a href="{{ url('/koleksi') }}?kategori=aksesoris" class="chip">Aksesoris</a>
        </div>

        <form class="sort-form" action="{{ url('/koleksi') }}" method="GET">
          <label class="sort-select">
            <span>Urutkan:</span>
            <select name="urut" onchange="this.form.submit()">
              <option value="terbaru" selected>Terbaru</option>
              <option value="harga-terendah">Harga terendah</option>
              <option value="harga-tertinggi">Harga tertinggi</option>
              <option value="populer">Terpopuler</option>
            </select>
          </label>
        </form>
      </div>

      <!-- Grid produk: satu .produk-card untuk setiap produk -->
      <div class="produk-grid">
        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/bajuputih.png') }}" alt="Tulle Sage Green">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Tulle Sage Green</h3>
          <p class="produk-size">Ukuran M-XL</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 260.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/bajuhitam.png') }}" alt="Brokat Hitam">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Brokat Hitam Payet</h3>
          <p class="produk-size">Ukuran S-M</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 260.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/produk-modern-navy.png') }}" alt="Modern Navy Lace">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Modern Navy Lace</h3>
          <p class="produk-size">Ukuran M-L</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 240.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/sepatu1.png') }}" alt="Heels Cream Bros">
            <span class="produk-badge">Heels</span>
          </div>
          <h3 class="produk-name">Heels Cream Bros</h3>
          <p class="produk-size">Size 37-40</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 75.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/sepatu2.png') }}" alt="Heels Slingback">
            <span class="produk-badge">Heels</span>
          </div>
          <h3 class="produk-name">Heels Slingback</h3>
          <p class="produk-size">Size 36-40</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 85.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/produk-encim-gold.png') }}" alt="Anting Mutiara">
            <span class="produk-badge">Aksesoris</span>
          </div>
          <h3 class="produk-name">Anting Mutiara</h3>
          <p class="produk-size">Satu ukuran</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 35.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/kemben.png') }}" alt="Kemben Satin Cream">
            <span class="produk-badge">Kemben</span>
          </div>
          <h3 class="produk-name">Kemben Satin Cream</h3>
          <p class="produk-size">Satu ukuran</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 50.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/kategori-kemben.png') }}" alt="Kemben Brokat">
            <span class="produk-badge">Kemben</span>
          </div>
          <h3 class="produk-name">Kemben Brokat</h3>
          <p class="produk-size">Satu ukuran</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 30.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-image">
            <img src="{{ asset('images/produk-brokat-maroon.png') }}" alt="Kain Batik Parang">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Kain Batik Parang</h3>
          <p class="produk-size">Panjang 2 m</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 120.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Cara sewa -->
  <section class="langkah-section">
    <div class="container">
      <h2 class="block-title">Cara sewa</h2>
      <div class="langkah-grid">
        <div class="langkah-card">
          <span class="langkah-number">1</span>
          <h3>Pilih busana</h3>
          <p>Telusuri koleksi, lalu catat ukuran dan tanggal acara Anda.</p>
        </div>
        <div class="langkah-card">
          <span class="langkah-number">2</span>
          <h3>Pesan lewat WhatsApp</h3>
          <p>Kirim nama produk dan tanggal. Kami cek ketersediaan dan konfirmasi.</p>
        </div>
        <div class="langkah-card">
          <span class="langkah-number">3</span>
          <h3>Ambil dan kembalikan</h3>
          <p>Ambil di toko atau kirim ke alamat Anda. Kembalikan sesuai tanggal sewa.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Galeri momen -->
  <section class="momen-section">
    <div class="container">
      <div class="momen-header">
        <h2 class="block-title">Galeri momen</h2>
        <a href="#" class="momen-link">Lihat lebih banyak &rarr;</a>
      </div>

      <div class="momen-grid">
        <div class="momen-card momen-tall">
          <img src="{{ asset('images/foto2.png') }}" alt="Brokat Maroon">
          <div class="momen-caption">
            <span class="momen-tag">Wisuda</span>
            <span class="momen-name">Brokat Maroon</span>
          </div>
        </div>

        <div class="momen-stack">
          <div class="momen-card">
            <img src="{{ asset('images/foto3.png') }}" alt="Tulle Sage Green">
            <div class="momen-caption">
              <span class="momen-tag">Pesta</span>
              <span class="momen-name">Tulle Sage Green</span>
            </div>
          </div>
          <div class="momen-card">
            <img src="{{ asset('images/foto4.png') }}" alt="Modern Navy Lane">
            <div class="momen-caption">
              <span class="momen-tag">Wisuda</span>
              <span class="momen-name">Modern Navy Lane</span>
            </div>
          </div>
        </div>

        <div class="momen-card momen-tall">
          <img src="{{ asset('images/foto5.png') }}" alt="Kebaya Putih Akad">
          <div class="momen-caption">
            <span class="momen-tag">Lamaran</span>
            <span class="momen-name">Kebaya Putih Akad</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimoni -->
  <section class="testimoni-section">
    <div class="container">
      <h2 class="block-title">Kata pelanggan</h2>
      <div class="testimoni-grid">
        <div class="testimoni-card">
          <p class="testimoni-text">Kebayanya bersih, pas di badan, dan datang tepat waktu untuk wisuda saya.</p>
          <span class="testimoni-name">Andra, Palangka Raya</span>
        </div>
        <div class="testimoni-card">
          <p class="testimoni-text">Heels-nya nyaman dipakai seharian. Admin membalas cepat dan sangat membantu.</p>
          <span class="testimoni-name">Justin, Banjarmasin</span>
        </div>
        <div class="testimoni-card">
          <p class="testimoni-text">Sewa satu paket kebaya dan aksesoris jauh lebih hemat daripada beli.</p>
          <span class="testimoni-name">Bude, Sampit</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Ajakan hubungi -->
  <section class="cta-section">
    <div class="container cta-wrapper">
      <h2 class="cta-title">Tidak menemukan yang dicari?</h2>
      <p class="cta-desc">Hubungi kami, tim CABIL.RENT akan bantu carikan pilihan yang sesuai.</p>
      <a href="#" class="btn-cta">Chat WhatsApp</a>
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
            <a href="#" class="social-circle" title="Instagram">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
              </svg>
            </a>
            <a href="#" class="social-circle" title="WhatsApp">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
              </svg>
            </a>
            <a href="#" class="social-circle" title="TikTok">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.46 6.27 6.27 0 0 0 1.88-4.48V8.34a8.16 8.16 0 0 0 4.89 1.6V6.5a4.85 4.85 0 0 1-.99-.01z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Menu jelajahi -->
        <div class="footer-col">
          <h4>JELAJAHI</h4>
          <ul>
            <li><a href="{{ url('/home') }}">Home</a></li>
            <li><a href="{{ url('/koleksi') }}">Collection</a></li>
            <li><a href="#">About</a></li>
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
