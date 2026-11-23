<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-col brand-col">
        <div class="footer-brand">
          <img src="{{ asset('images/logo.png') }}" alt="Logo cabil.rent" class="footer-logo-img" loading="lazy" decoding="async">
          <span class="brand-name">CABIL.RENT</span>
        </div>
        <p>Sewa kebaya akad, wisuda, pesta, dan adat yang pas di badan, rapi, dan siap pakai.</p>
        <div class="footer-socials">
          <a href="https://www.instagram.com/cabil.rent" class="social-icon" title="Instagram" target="_blank" rel="noopener">
            <img src="{{ asset('images/instagram.png') }}" alt="Instagram" loading="lazy" decoding="async">
          </a>
          <a href="https://wa.me/{{ config('services.whatsapp.admin_number', '6287894302894') }}" class="social-icon" title="WhatsApp" target="_blank" rel="noopener">
            <img src="{{ asset('images/wa.png') }}" alt="WhatsApp" loading="lazy" decoding="async">
          </a>
        </div>
      </div>

      <div class="footer-col">
        <h4>JELAJAHI</h4>
        <ul>
          <li><a href="{{ url('/') }}">Beranda</a></li>
          <li><a href="{{ url('/koleksi') }}">Collection</a></li>
          <li><a href="{{ url('/tentang') }}">About</a></li>
          <li><a href="{{ url('/kontak') }}">Contact</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>KATEGORI</h4>
        <ul>
          <li><a href="{{ url('/koleksi') }}?kategori=kebaya">Paket Kebaya</a></li>
          <li><a href="{{ url('/koleksi') }}?kategori=heels">Heels</a></li>
          <li><a href="{{ url('/koleksi') }}?kategori=kamisol">Kamisol</a></li>
        </ul>
      </div>

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

    <div class="footer-bottom">
      <p>&copy; 2026 cabil.rent , Semua hak dilindungi.</p>
      <div class="footer-legal">
        <a href="#">Syarat &amp; Ketentuan</a>
        <a href="#">Kebijakan Privasi</a>
      </div>
    </div>
  </div>
</footer>
