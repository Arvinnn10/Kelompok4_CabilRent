<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan - Cabil.Rent</title>

  <link rel="stylesheet" href="{{ asset_versioned('css/keranjang.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/nav-search.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
  <style>
    .riwayat-section {
      padding: 40px 0 80px;
      min-height: 70vh;
      background-color: #faf6f2;
    }
    .riwayat-header {
      margin-bottom: 28px;
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      flex-wrap: wrap;
      gap: 16px;
      border-bottom: 1px solid #ebdcd0;
      padding-bottom: 20px;
    }
    .riwayat-title {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 32px;
      font-weight: 600;
      color: #33241b;
      margin-bottom: 6px;
    }
    .riwayat-desc {
      font-size: 14px;
      color: #786558;
    }
    .btn-kembali {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #623e2b;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      padding: 8px 16px;
      background: #ffffff;
      border: 1px solid #ebdcd0;
      border-radius: 10px;
      transition: all 0.2s ease;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    .btn-kembali:hover {
      background-color: #623e2b !important;
      color: #ffffff !important;
      border-color: #623e2b !important;
    }
    .btn-kembali svg {
      transition: transform 0.2s ease;
    }
    .btn-kembali:hover svg {
      transform: translateX(-3px);
    }
    .btn-sewa-lagi {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      background-color: #623e2b;
      color: #ffffff;
      font-size: 13.5px;
      font-weight: 600;
      border-radius: 10px;
      text-decoration: none;
      transition: background-color 0.2s ease;
    }
    .btn-sewa-lagi:hover {
      background-color: #4d2f20;
    }
    .order-group-card {
      background: #ffffff;
      border: 1px solid #e8dbcf;
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 2px 12px rgba(98, 62, 43, 0.04);
      transition: box-shadow 0.2s ease;
    }
    .order-group-card:hover {
      box-shadow: 0 6px 20px rgba(98, 62, 43, 0.08);
    }
    .order-group-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      padding-bottom: 16px;
      border-bottom: 1px dashed #e8dbcf;
      margin-bottom: 16px;
    }
    .order-invoice-num {
      font-size: 16px;
      font-weight: 700;
      color: #623e2b;
      letter-spacing: 0.02em;
    }
    .order-date-text {
      font-size: 13px;
      color: #8c786c;
      margin-left: 8px;
    }
    .badge-status {
      display: inline-block;
      padding: 5px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      text-transform: capitalize;
    }
    .status-pending,
    .status-menunggu {
      background-color: #fef3c7;
      color: #92400e;
    }
    .status-dikonfirmasi {
      background-color: #e0e7ff;
      color: #3730a3;
    }
    .status-diproses {
      background-color: #dbeafe;
      color: #1e40af;
    }
    .status-sedang_dipinjam,
    .status-disewa {
      background-color: #e0f2fe;
      color: #0369a1;
    }
    .status-selesai {
      background-color: #f1f5f9;
      color: #475569;
    }
    .status-dibatalkan {
      background-color: #fee2e2;
      color: #991b1b;
    }
    .order-item-list {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }
    .order-item-row {
      display: flex;
      align-items: center;
      gap: 16px;
      padding-bottom: 14px;
      border-bottom: 1px solid #f3ece4;
    }
    .order-item-row:last-child {
      border-bottom: none;
    }
    .order-item-img {
      width: 64px;
      height: 64px;
      border-radius: 10px;
      object-fit: cover;
      background-color: #eeddd0;
      flex-shrink: 0;
    }
    .order-item-details {
      flex: 1;
      min-width: 0;
    }
    .order-item-name {
      font-size: 15px;
      font-weight: 600;
      color: #33241b;
      margin-bottom: 3px;
    }
    .order-item-meta {
      font-size: 13px;
      color: #8c786c;
    }
    .order-item-subtotal {
      font-size: 15px;
      font-weight: 700;
      color: #623e2b;
      text-align: right;
    }
    .order-group-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
      margin-top: 18px;
      background-color: #fcf9f6;
      padding: 16px 20px;
      border-radius: 12px;
      border: 1px solid #f0e6dd;
    }
    .order-total-info {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
    .order-total-label {
      font-size: 12.5px;
      color: #8c786c;
    }
    .order-total-val {
      font-size: 18px;
      font-weight: 800;
      color: #623e2b;
    }
    .btn-hubungi-wa {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background-color: #25d366;
      color: #ffffff;
      padding: 9px 20px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: background-color 0.2s;
    }
    .btn-hubungi-wa:hover {
      background-color: #1ebd5b;
    }
    .empty-state {
      text-align: center;
      padding: 64px 20px;
      background: #ffffff;
      border: 1px dashed #d9c8b8;
      border-radius: 16px;
    }
    .empty-icon {
      width: 56px;
      height: 56px;
      margin: 0 auto 16px;
      color: #a49184;
    }
  </style>
</head>
<body>

  <!-- Navigasi atas -->
  @include('layouts.partials.navbar')

  <div class="riwayat-section">
    <div class="container">
      <!-- Tombol kembali ke home -->
      <div style="margin-bottom: 20px;">
        <a href="{{ url('/') }}" class="btn-kembali" id="btn-kembali-home">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali ke Beranda
        </a>
      </div>

      <div class="riwayat-header">
        <div>
          <h1 class="riwayat-title">Riwayat Pesanan Saya</h1>
          <p class="riwayat-desc">Pantau status penyewaan busana, jadwal sewa, dan bukti transaksi Anda.</p>
        </div>
        <div>
          <a href="{{ url('/koleksi') }}" class="btn-sewa-lagi">+ Sewa Busana Lain</a>
        </div>
      </div>

      @if ($orderGroups->isEmpty())
        <div class="empty-state">
          <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
          <h2 style="font-size: 20px; color: #33241b; margin-bottom: 8px;">Belum Ada Riwayat Pesanan</h2>
          <p style="color: #6e5e54; margin-bottom: 24px; font-size: 14px;">Anda belum melakukan penyewaan kebaya, heels, atau kamisol di CabilRent.</p>
          <a href="{{ url('/koleksi') }}" class="btn-sewa-lagi" style="display: inline-flex;">Jelajahi Koleksi</a>
        </div>
      @else
        @foreach ($orderGroups as $invoiceCode => $group)
          @php
            $firstOrder = $group->first();
            $status = $firstOrder->status_pesan ?? 'pending';
            $tglSewa = $firstOrder->tanggal_sewa ? \Carbon\Carbon::parse($firstOrder->tanggal_sewa)->format('d M Y') : '-';
            $tglKembali = $firstOrder->tanggal_kembali ? \Carbon\Carbon::parse($firstOrder->tanggal_kembali)->format('d M Y') : '-';
            $totalGroupPrice = $group->sum('total_harga');
          @endphp
          <article class="order-group-card">
            <div class="order-group-header">
              <div>
                <span class="order-invoice-num">#{{ $invoiceCode }}</span>
                <span class="order-date-text">Dipesan pada {{ $firstOrder->created_at->format('d M Y, H:i') }} WIB</span>
              </div>
              <span class="badge-status status-{{ $status }}">{{ ['pending' => 'Menunggu', 'dikonfirmasi' => 'Dikonfirmasi', 'sedang_dipinjam' => 'Sedang Dipinjam', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'][$status] ?? ucfirst($status) }}</span>
            </div>

            <div style="margin-bottom: 16px; font-size: 13px; color: #6e5e54; display: flex; gap: 24px; flex-wrap: wrap;">
              <div><strong>Jadwal Sewa:</strong> {{ $tglSewa }} &ndash; {{ $tglKembali }}</div>
              <div><strong>Tujuan Acara:</strong> {{ $firstOrder->domisili ?? 'Ambil di Butik' }}</div>
              <div><strong>Jumlah Busana:</strong> {{ $group->count() }} item</div>
            </div>

            <div class="order-item-list">
              @foreach ($group as $order)
                @php
                  $product = $order->basket->product ?? null;
                  $productName = $product->nama_produk ?? 'Koleksi Busana';
                  $categoryName = $product->category->nama_kategori ?? 'Koleksi';
                  $imgSrc = asset('images/logo.png');
                  if ($product && $product->foto_produk) {
                    $imgSrc = str_starts_with($product->foto_produk, 'images/') || str_starts_with($product->foto_produk, 'http')
                              ? asset($product->foto_produk)
                              : asset('images/koleksi/' . $product->foto_produk);
                  }
                @endphp
                <div class="order-item-row">
                  <img src="{{ $imgSrc }}" alt="{{ $productName }}" class="order-item-img"
                       onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                  <div class="order-item-details">
                    <h3 class="order-item-name">{{ $productName }}</h3>
                    <div class="order-item-meta">
                      <span>Kategori: {{ $categoryName }}</span> &bull;
                      <span>Ukuran: {{ $product->ukuran ?? 'All Size' }}</span> &bull;
                      <span>Qty: {{ $order->basket->jumlah ?? 1 }} pcs</span>
                    </div>
                  </div>
                  <div class="order-item-subtotal">
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                  </div>
                </div>
              @endforeach
            </div>

            <div class="order-group-footer">
              <div class="order-total-info">
                <span class="order-total-label">Total Pembayaran Awal (Termasuk Deposit Pengembalian Rp 100.000 / item)</span>
                <span class="order-total-val">Rp {{ number_format($totalGroupPrice, 0, ',', '.') }}</span>
              </div>
              <a href="https://wa.me/{{ config('services.whatsapp.admin_number', '6287894302894') }}?text={{ urlencode('Halo Admin CabilRent, saya ingin menanyakan status pesanan saya dengan nomor invoice #' . $invoiceCode) }}"
                 class="btn-hubungi-wa" target="_blank" rel="noopener">
                <img src="{{ asset('images/wa.png') }}" alt="" width="18" height="18">
                Konfirmasi via WhatsApp
              </a>
            </div>
          </article>
        @endforeach
      @endif
    </div>
  </div>

  <!-- Bagian footer (sama dengan halaman lainnya) -->
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
</body>
</html>