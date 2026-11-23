@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Cabil.Rent')

@section('styles')
  <link rel="stylesheet" href="{{ asset_versioned('css/keranjang.css') }}">
  <style>
    .riwayat-section {
      padding: 48px 0 80px;
      min-height: 70vh;
      background-color: #fdfaf6;
    }
    .riwayat-header {
      margin-bottom: 32px;
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      flex-wrap: wrap;
      gap: 16px;
      border-bottom: 1px solid #ebd9c8;
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
      font-size: 15px;
      color: #6e5e54;
    }
    .riwayat-actions {
      display: flex;
      gap: 12px;
      align-items: center;
    }
    .btn-logout-cust {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 9px 18px;
      border: 1px solid #d4c2b3;
      border-radius: 999px;
      background: #ffffff;
      color: #7a2e2e;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .btn-logout-cust:hover {
      background: #faebeb;
      border-color: #d99b9b;
    }
    .order-group-card {
      background: #ffffff;
      border: 1px solid #e8dbcf;
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 4px 16px rgba(98, 62, 43, 0.04);
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
      font-size: 17px;
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
    .status-disewa {
      background-color: #dcfce7;
      color: #166534;
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
      padding-top: 16px;
      background-color: #fcf9f6;
      padding: 16px 20px;
      border-radius: 12px;
    }
    .order-total-info {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
    .order-total-label {
      font-size: 13px;
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
      width: 64px;
      height: 64px;
      margin: 0 auto 16px;
      color: #a49184;
    }
  </style>
@endsection

@section('content')
  <div class="riwayat-section">
    <div class="container">
      <div class="riwayat-header">
        <div>
          <h1 class="riwayat-title">Riwayat Pesanan Saya</h1>
          <p class="riwayat-desc">Pantau status penyewaan busana, konfirmasi tanggal acara, dan bukti transaksi Anda.</p>
        </div>
        <div class="riwayat-actions">
          <a href="{{ url('/koleksi') }}" class="btn-checkout" style="padding: 10px 20px; font-size: 13px;">+ Sewa Lagi</a>
          <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn-logout-cust" title="Keluar dari akun">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              Keluar
            </button>
          </form>
        </div>
      </div>

      @if ($orderGroups->isEmpty())
        <div class="empty-state">
          <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
          <h2 style="font-size: 20px; color: #33241b; margin-bottom: 8px;">Belum Ada Riwayat Pesanan</h2>
          <p style="color: #6e5e54; margin-bottom: 24px;">Anda belum melakukan penyewaan kebaya, heels, atau kamisol di CabilRent.</p>
          <a href="{{ url('/koleksi') }}" class="btn-checkout" style="display: inline-block; padding: 12px 28px;">Jelajahi Koleksi</a>
        </div>
      @else
        @foreach ($orderGroups as $invoiceCode => $group)
          @php
            $firstOrder = $group->first();
            $status = $firstOrder->status_pesan ?? 'menunggu';
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
              <span class="badge-status status-{{ $status }}">{{ ucfirst($status) }}</span>
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
@endsection
