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
        <li><a href="{{ url('/home') }}" class="nav-link active">Home</a></li>
         <li><a href="{{ url('/') }}" class="nav-link">Koleksi</a></li>
        <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang</a></li>
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
          <img src="{{ asset('images/koleksi/foto1.png') }}" alt="Koleksi Terbaru 2026">
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
            <img src="{{ asset('images/koleksi/kategori-kebaya.png') }}" alt="Pakaian Kebaya">
          </div>
          <div class="kategori-info">
            <span class="kategori-name">Pakaian Kebaya</span>
            <span class="kategori-count">9 produk</span>
          </div>
        </a>

        <a href="{{ url('/koleksi') }}?kategori=heels" class="kategori-card">
          <div class="kategori-image">
            <img src="{{ asset('images/koleksi/sepatu1.png') }}" alt="Heels">
          </div>
          <div class="kategori-info">
            <span class="kategori-name">Heels</span>
            <span class="kategori-count">4 produk</span>
          </div>
        </a>

        <a href="{{ url('/koleksi') }}?kategori=kamisol" class="kategori-card">
          <div class="kategori-image">
            <img src="{{ asset('images/koleksi/kemben.png') }}" alt="Kamisol">
          </div>
          <div class="kategori-info">
            <span class="kategori-name">Kamisol</span>
            <span class="kategori-count">3 produk</span>
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
        <span class="katalog-count" id="katalog-count">16 produk</span>
      </div>

      <!-- Filter kategori (js/koleksi.js) & urutan, juga dikirim sebagai query string ?kategori=...&urut=... -->
      <div class="katalog-toolbar">
        <div class="filter-chips">
          <a href="{{ url('/koleksi') }}" class="chip active" data-filter="semua">Semua</a>
          <a href="{{ url('/koleksi') }}?kategori=kebaya" class="chip" data-filter="kebaya">Pakaian Kebaya</a>
          <a href="{{ url('/koleksi') }}?kategori=heels" class="chip" data-filter="heels">Heels</a>
          <a href="{{ url('/koleksi') }}?kategori=kamisol" class="chip" data-filter="kamisol">Kamisol</a>
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

      <!-- Grid produk: satu .produk-card untuk setiap produk, data-kategori = kebaya | heels | kamisol -->
      <div class="produk-grid">
        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-coco.png') }}" alt="Coco Kebaya">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Coco Kebaya</h3>
          <p class="produk-size">inc : rok, kamisol, selendang dan hijab</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 200.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-ivory-palazzo.png') }}" alt="Ivory Palazzo Kebaya">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Ivory Palazzo Kebaya</h3>
          <p class="produk-size">inc : rok, kamisol, selendang dan hijab</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 300.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-aira.png') }}" alt="Aira Kebaya">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Aira Kebaya</h3>
          <p class="produk-size">inc : rok, kamisol, selendang dan hijab</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 450.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-ayana.png') }}" alt="Ayana Kebaya">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Ayana Kebaya</h3>
          <p class="produk-size">inc : rok, kamisol, selendang dan hijab</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 250.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-ayuna.png') }}" alt="Ayuna Kebaya (non hijab)">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Ayuna Kebaya (non hijab)</h3>
          <p class="produk-size">inc : rok, kamisol, dan selendang</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 300.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-kiona.png') }}" alt="Kiona Kebaya">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Kiona Kebaya</h3>
          <p class="produk-size">inc : rok, kamisol, selendang dan hijab</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 350.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-meira.png') }}" alt="Meira Kebaya">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Meira Kebaya</h3>
          <p class="produk-size">inc : rok, kamisol, selendang dan hijab</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 350.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-arella.png') }}" alt="Arella Kebaya">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Arella Kebaya</h3>
          <p class="produk-size">inc : rok, kamisol, dan hijab</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 350.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kebaya">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kebaya-rose.png') }}" alt="Rose Kebaya">
            <span class="produk-badge">Pakaian Kebaya</span>
          </div>
          <h3 class="produk-name">Rose Kebaya</h3>
          <p class="produk-size">inc : rok, kamisol, dan hijab</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 650.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="heels">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/heels-arin.png') }}" alt="Arin Heels">
            <span class="produk-badge">Heels</span>
          </div>
          <h3 class="produk-name">Arin Heels</h3>
          <p class="produk-size">heels 5cm ukuran 37 cm</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 50.000/3 hari</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="heels">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/heels-aqila.png') }}" alt="Aqila Heels">
            <span class="produk-badge">Heels</span>
          </div>
          <h3 class="produk-name">Aqila Heels</h3>
          <p class="produk-size">heels 7cm ukuran 39 cm</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 40.000/3 hari</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="heels">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/heels-aura.png') }}" alt="Aura Heels">
            <span class="produk-badge">Heels</span>
          </div>
          <h3 class="produk-name">Aura Heels</h3>
          <p class="produk-size">heels 5cm ukuran 37 cm</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 45.000/3 hari</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="heels">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/heels-kayla.png') }}" alt="Kayla Heels">
            <span class="produk-badge">Heels</span>
          </div>
          <h3 class="produk-name">Kayla Heels</h3>
          <p class="produk-size">heels 5cm ukuran 37 cm</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 50.000/3 hari</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kamisol">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kamisol-m.png') }}" alt="Kamisol">
            <span class="produk-badge">Kamisol</span>
          </div>
          <h3 class="produk-name">Kamisol</h3>
          <p class="produk-size">Ukuran M</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 200.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kamisol">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kamisol-s.png') }}" alt="Kamisol">
            <span class="produk-badge">Kamisol</span>
          </div>
          <h3 class="produk-name">Kamisol</h3>
          <p class="produk-size">Ukuran S</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 300.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>

        <div class="produk-card" data-kategori="kamisol">
          <div class="produk-image">
            <img src="{{ asset('images/koleksi/kamisol-l.png') }}" alt="Kamisol">
            <span class="produk-badge">Kamisol</span>
          </div>
          <h3 class="produk-name">Kamisol</h3>
          <p class="produk-size">Ukuran L</p>
          <div class="produk-footer">
            <span class="produk-price">Rp 450.000</span>
            <a href="#" class="btn-sewa">Sewa</a>
          </div>
        </div>
      </div>

      <p class="produk-empty" id="produk-empty" hidden>Belum ada produk di kategori ini.</p>
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
          <img src="{{ asset('images/koleksi/foto2.png') }}" alt="Brokat Maroon">
          <div class="momen-caption">
            <span class="momen-tag">Wisuda</span>
            <span class="momen-name">Brokat Maroon</span>
          </div>
        </div>

        <div class="momen-stack">
          <div class="momen-card">
            <img src="{{ asset('images/koleksi/foto3.png') }}" alt="Tulle Sage Green">
            <div class="momen-caption">
              <span class="momen-tag">Pesta</span>
              <span class="momen-name">Tulle Sage Green</span>
            </div>
          </div>
          <div class="momen-card">
            <img src="{{ asset('images/koleksi/foto4.png') }}" alt="Modern Navy Lane">
            <div class="momen-caption">
              <span class="momen-tag">Wisuda</span>
              <span class="momen-name">Modern Navy Lane</span>
            </div>
          </div>
        </div>

        <div class="momen-card momen-tall">
          <img src="{{ asset('images/koleksi/foto5.png') }}" alt="Kebaya Putih Akad">
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
            <!-- Ganti file ikon di folder images/ (ukuran diatur di CSS) -->
            <a href="#" class="social-icon" title="Instagram">
              <img src="{{ asset('images/icon-instagram.png') }}" alt="Instagram">
            </a>
            <a href="#" class="social-icon" title="WhatsApp">
              <img src="{{ asset('images/icon-whatsapp.png') }}" alt="WhatsApp">
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

  <script src="{{ asset('js/koleksi.js') }}"></script>
</body>
</html>
