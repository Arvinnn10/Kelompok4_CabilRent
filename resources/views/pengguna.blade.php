<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengguna - Cabil.Rent</title>

  <!-- Tata letak admin (sidebar, bar atas, kartu, badge) dipakai bersama dashboard -->
  <link rel="stylesheet" href="{{ asset_versioned('css/dashboard.css') }}">
  <!-- CSS khusus halaman Pengguna -->
  <link rel="stylesheet" href="{{ asset_versioned('css/pengguna.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">
</head>
<body>

  <div class="app-container" id="app">
    <!-- Sidebar navigasi -->
    <aside class="sidebar" id="sidebar">
      <div>
        <div class="sidebar-header">
          <img src="{{ asset('images/logo.png') }}" alt="Logo cabil.rent" class="sidebar-logo" decoding="async">
          <div>
            <span class="sidebar-brand">CABIL.RENT</span>
            <span class="sidebar-sub">ADMIN PANEL</span>
          </div>
        </div>

        <nav class="sidebar-nav">
          <p class="nav-section">UTAMA</p>
          <a href="{{ url('/dashboard') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="3" width="7" height="5" rx="1.5"></rect>
              <rect x="14" y="12" width="7" height="9" rx="1.5"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
            <span>Dashboard</span>
          </a>
          <a href="{{ url('/pesanan') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2z"></path>
              <line x1="9" y1="8" x2="15" y2="8"></line>
              <line x1="9" y1="12" x2="15" y2="12"></line>
            </svg>
            <span>Pesanan</span>
          </a>

          <p class="nav-section">KATALOG</p>
          <a href="{{ url('/produk') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
              <polyline points="3.3 7 12 12 20.7 7"></polyline>
              <line x1="12" y1="22" x2="12" y2="12"></line>
            </svg>
            <span>Produk</span>
          </a>
          <a href="{{ url('/kategori') }}" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
              <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            <span>Kategori</span>
          </a>

          <p class="nav-section">PENGGUNA</p>
          <a href="{{ url('/pengguna') }}" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="9" cy="8" r="4"></circle>
              <path d="M2 21a7 7 0 0 1 14 0"></path>
              <path d="M16 4a4 4 0 0 1 0 8"></path>
              <path d="M22 21a7 7 0 0 0-4-6.3"></path>
            </svg>
            <span>Pengguna</span>
          </a>
        </nav>
      </div>

      <!-- Profil admin dan tombol keluar -->
      <div class="sidebar-footer">
        <div class="admin-card">
          <span class="avatar avatar-dark">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->username ?? 'A', 0, 1)) }}</span>
          <div class="admin-info">
            <span class="admin-name">{{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</span>
            <span class="admin-email">{{ Auth::user()->email }}</span>
          </div>
        </div>
        <button type="button" class="btn-keluar" data-open-logout>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <div class="main-area">
      <!-- Bar atas -->
      <header class="topbar">
        <span class="icon-btn icon-static" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            <polyline points="3.3 7 12 12 20.7 7"></polyline>
            <line x1="12" y1="22" x2="12" y2="12"></line>
          </svg>
        </span>

        <div class="search-bar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" placeholder="Cari nama kebaya" aria-label="Cari nama kebaya">
        </div>

        <div class="topbar-actions">
          <!-- Notifikasi -->
          <div class="dropdown">
            <button type="button" class="icon-btn btn-notif" id="btn-notif" title="Notifikasi"
                    aria-haspopup="true" aria-expanded="false" aria-controls="menu-notif">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
            </button>

            <div class="dropdown-menu notif-menu" id="menu-notif" hidden>
              <div class="notif-head">
                <span class="notif-title">Notifikasi</span>
                <button type="button" class="link-btn" id="btn-baca">Tandai dibaca</button>
              </div>
              <ul class="notif-list">
                <!-- BACKEND: ganti dengan daftar notifikasi asli -->
                <li class="notif-item is-unread">
                  <p class="notif-judul">Pesanan baru CR-1044</p>
                  <p class="notif-detail">Bella Safira · 12 Des</p>
                </li>
                <li class="notif-item is-unread">
                  <p class="notif-judul">Pesanan baru CR-1043</p>
                  <p class="notif-detail">Anisa Rahma · 3 Des</p>
                </li>
                <li class="notif-item is-unread">
                  <p class="notif-judul">Aura Heels tinggal 2</p>
                  <p class="notif-detail">Perlu restok</p>
                </li>
              </ul>
            </div>
          </div>

          <!-- Akun -->
          <div class="dropdown">
            <button type="button" class="avatar avatar-light avatar-btn" id="btn-akun" title="Akun"
                    aria-haspopup="true" aria-expanded="false" aria-controls="menu-akun">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->username ?? 'A', 0, 1)) }}</button>

            <div class="dropdown-menu akun-menu" id="menu-akun" hidden>
              <div class="akun-head">
                <p class="akun-nama">{{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</p>
                <p class="akun-email">{{ Auth::user()->email }}</p>
              </div>
              <button type="button" class="akun-keluar" data-open-logout>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                  <polyline points="16 17 21 12 16 7"></polyline>
                  <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Keluar</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      <main class="main-content">
        <h1 class="page-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <circle cx="9" cy="8" r="4"></circle>
            <path d="M2 21a7 7 0 0 1 14 0"></path>
            <path d="M16 4a4 4 0 0 1 0 8"></path>
            <path d="M22 21a7 7 0 0 0-4-6.3"></path>
          </svg>
          Pengguna
        </h1>

        @if (session('success'))
          <p class="flash-success">{{ session('success') }}</p>
        @endif

        <!-- Pencarian dan jumlah pelanggan -->
        <div class="pg-toolbar">
          <form method="GET" action="{{ url('/pengguna') }}" class="pg-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna" aria-label="Cari pengguna">
          </form>
          <!-- BACKEND: jumlah pelanggan (contoh dari desain bila $users belum dikirim) -->
          <p class="pg-jumlah">{{ isset($users) ? $users->total() : 6 }} pelanggan</p>
        </div>

        <!-- Tabel pengguna -->
        <section class="panel table-panel pg-panel">
          <div class="table-scroll">
            <table class="order-table pg-table">
              <thead>
                <tr>
                  <th>Pengguna</th>
                  <th>WhatsApp</th>
                  <th>Bergabung</th>
                  <th>Pesanan</th>
                  <th>Total belanja</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @if (isset($users))
                  {{-- BACKEND: $users = pengguna (paginate) dengan withCount('orders') dan withSum('orders', 'total_harga'); $user->status = 'aktif' | 'diblokir' --}}
                  @forelse ($users as $user)
                    @php
                      $nama = $user->nama_lengkap ?? $user->username;
                      $kata = preg_split('/\s+/', trim($nama));
                      $inisial = strtoupper(substr($kata[0] ?? '', 0, 1) . substr($kata[1] ?? '', 0, 1));
                      $diblokir = ($user->status ?? 'aktif') === 'diblokir';
                      $riwayat = $user->orders->map(function ($o) {
                          return [
                              'id' => 'CR-' . str_pad($o->id, 4, '0', STR_PAD_LEFT),
                              'produk' => $o->product->nama_produk ?? '-',
                              'desk' => $o->product->desk_produk ?? '',
                              'status' => $o->status_pesan,
                          ];
                      });
                    @endphp
                    <tr>
                      <td>
                        <div class="pg-user">
                          <span class="avatar avatar-md">{{ $inisial }}</span>
                          <span class="pg-nama">{{ $nama }}</span>
                        </div>
                      </td>
                      <td class="pg-wa">{{ $user->no_telp }}</td>
                      <td>{{ $user->created_at->locale('id')->translatedFormat('j M Y') }}</td>
                      <td>{{ $user->orders_count ?? 0 }} kali</td>
                      <td class="pg-total">Rp {{ number_format($user->orders_sum_total_harga ?? 0, 0, ',', '.') }}</td>
                      <td><span class="badge status-{{ $diblokir ? 'diblokir' : 'aktif' }}">{{ $diblokir ? 'Diblokir' : 'Aktif' }}</span></td>
                      <td>
                        <div class="pg-aksi">
                          <button type="button" class="btn-lihat" title="Lihat detail"
                                  data-nama="{{ $nama }}"
                                  data-inisial="{{ $inisial }}"
                                  data-whatsapp="{{ $user->no_telp }}"
                                  data-bergabung="{{ $user->created_at->locale('id')->translatedFormat('j M Y') }}"
                                  data-pesanan="{{ $user->orders_count ?? 0 }}"
                                  data-total="Rp {{ number_format($user->orders_sum_total_harga ?? 0, 0, ',', '.') }}"
                                  data-status="{{ $diblokir ? 'diblokir' : 'aktif' }}"
                                  data-riwayat="{{ $riwayat->toJson() }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                          </button>
                          <!-- BACKEND: route PATCH /pengguna/{id}/blokir untuk memblokir / mengaktifkan -->
                          <form action="{{ url('/pengguna/' . $user->id . '/blokir') }}" method="POST"
                                data-konfirmasi="{{ $diblokir ? 'Aktifkan' : 'Blokir' }} {{ $nama }}?">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-blokir">{{ $diblokir ? 'Aktifkan' : 'Blokir' }}</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="cell-kosong">Belum ada pengguna.</td>
                    </tr>
                  @endforelse
                @else
                  {{-- Contoh dari desain: tampil selama route belum mengirim $users --}}
                  <tr>
                    <td>
                      <div class="pg-user">
                        <span class="avatar avatar-md">NP</span>
                        <span class="pg-nama">Nadia Putri</span>
                      </div>
                    </td>
                    <td class="pg-wa">0812-3456-0001</td>
                    <td>2 Agu 2026</td>
                    <td>1 kali</td>
                    <td class="pg-total">Rp 300.000</td>
                    <td><span class="badge status-aktif">Aktif</span></td>
                    <td>
                      <div class="pg-aksi">
                        <button type="button" class="btn-lihat" title="Lihat detail"
                                data-nama="Nadia Putri" data-inisial="NP" data-whatsapp="0812-3456-0001"
                                data-bergabung="2 Agu 2026" data-pesanan="1" data-total="Rp 300.000"
                                data-status="aktif"
                                data-riwayat="[{&quot;id&quot;: &quot;CR-1042&quot;, &quot;produk&quot;: &quot;Coco Kebaya&quot;, &quot;desk&quot;: &quot;inc : rok, kamisol, selendang dan hijab&quot;, &quot;status&quot;: &quot;pending&quot;}]">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </button>
                        <button type="button" class="btn-blokir">Blokir</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="pg-user">
                        <span class="avatar avatar-md">RA</span>
                        <span class="pg-nama">Rani Amelia</span>
                      </div>
                    </td>
                    <td class="pg-wa">0812-3456-0002</td>
                    <td>14 Agu 2026</td>
                    <td>1 kali</td>
                    <td class="pg-total">Rp 300.000</td>
                    <td><span class="badge status-aktif">Aktif</span></td>
                    <td>
                      <div class="pg-aksi">
                        <button type="button" class="btn-lihat" title="Lihat detail"
                                data-nama="Rani Amelia" data-inisial="RA" data-whatsapp="0812-3456-0002"
                                data-bergabung="14 Agu 2026" data-pesanan="1" data-total="Rp 300.000"
                                data-status="aktif"
                                data-riwayat="[{&quot;id&quot;: &quot;CR-1041&quot;, &quot;produk&quot;: &quot;Arin Heels&quot;, &quot;desk&quot;: &quot;heels 5cm ukuran 37 cm&quot;, &quot;status&quot;: &quot;dikonfirmasi&quot;}]">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </button>
                        <button type="button" class="btn-blokir">Blokir</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="pg-user">
                        <span class="avatar avatar-md">IS</span>
                        <span class="pg-nama">Intan Sari</span>
                      </div>
                    </td>
                    <td class="pg-wa">0812-3456-0003</td>
                    <td>21 Sep 2026</td>
                    <td>1 kali</td>
                    <td class="pg-total">Rp 310.000</td>
                    <td><span class="badge status-aktif">Aktif</span></td>
                    <td>
                      <div class="pg-aksi">
                        <button type="button" class="btn-lihat" title="Lihat detail"
                                data-nama="Intan Sari" data-inisial="IS" data-whatsapp="0812-3456-0003"
                                data-bergabung="21 Sep 2026" data-pesanan="1" data-total="Rp 310.000"
                                data-status="aktif"
                                data-riwayat="[{&quot;id&quot;: &quot;CR-1040&quot;, &quot;produk&quot;: &quot;Aqila Heels&quot;, &quot;desk&quot;: &quot;heels 7cm ukuran 39 cm&quot;, &quot;status&quot;: &quot;dikonfirmasi&quot;}]">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </button>
                        <button type="button" class="btn-blokir">Blokir</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="pg-user">
                        <span class="avatar avatar-md">DL</span>
                        <span class="pg-nama">Dewi Lestari</span>
                      </div>
                    </td>
                    <td class="pg-wa">0812-3456-0004</td>
                    <td>3 Okt 2026</td>
                    <td>1 kali</td>
                    <td class="pg-total">Rp 260.000</td>
                    <td><span class="badge status-aktif">Aktif</span></td>
                    <td>
                      <div class="pg-aksi">
                        <button type="button" class="btn-lihat" title="Lihat detail"
                                data-nama="Dewi Lestari" data-inisial="DL" data-whatsapp="0812-3456-0004"
                                data-bergabung="3 Okt 2026" data-pesanan="1" data-total="Rp 260.000"
                                data-status="aktif"
                                data-riwayat="[{&quot;id&quot;: &quot;CR-1039&quot;, &quot;produk&quot;: &quot;Aira Kebaya&quot;, &quot;desk&quot;: &quot;inc : rok, kamisol, selendang dan hijab&quot;, &quot;status&quot;: &quot;selesai&quot;}]">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </button>
                        <button type="button" class="btn-blokir">Blokir</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="pg-user">
                        <span class="avatar avatar-md">SM</span>
                        <span class="pg-nama">Salsa Maharani</span>
                      </div>
                    </td>
                    <td class="pg-wa">0812-3456-0005</td>
                    <td>9 Okt 2026</td>
                    <td>1 kali</td>
                    <td class="pg-total">Rp 275.000</td>
                    <td><span class="badge status-aktif">Aktif</span></td>
                    <td>
                      <div class="pg-aksi">
                        <button type="button" class="btn-lihat" title="Lihat detail"
                                data-nama="Salsa Maharani" data-inisial="SM" data-whatsapp="0812-3456-0005"
                                data-bergabung="9 Okt 2026" data-pesanan="1" data-total="Rp 275.000"
                                data-status="aktif"
                                data-riwayat="[{&quot;id&quot;: &quot;CR-1038&quot;, &quot;produk&quot;: &quot;Kamisol Ivory&quot;, &quot;desk&quot;: &quot;Panjang 2 m&quot;, &quot;status&quot;: &quot;selesai&quot;}]">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </button>
                        <button type="button" class="btn-blokir">Blokir</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="pg-user">
                        <span class="avatar avatar-md">PA</span>
                        <span class="pg-nama">Putri Ayu</span>
                      </div>
                    </td>
                    <td class="pg-wa">0812-3456-0006</td>
                    <td>28 Okt 2026</td>
                    <td>0 kali</td>
                    <td class="pg-total">Rp 0</td>
                    <td><span class="badge status-diblokir">Diblokir</span></td>
                    <td>
                      <div class="pg-aksi">
                        <button type="button" class="btn-lihat" title="Lihat detail"
                                data-nama="Putri Ayu" data-inisial="PA" data-whatsapp="0812-3456-0006"
                                data-bergabung="28 Okt 2026" data-pesanan="0" data-total="Rp 0"
                                data-status="diblokir"
                                data-riwayat="[]">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </button>
                        <button type="button" class="btn-blokir">Aktifkan</button>
                      </div>
                    </td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>

          <!-- Navigasi halaman -->
          <div class="pg-pagination">
            <span class="pg-pagination-info">
              @if (isset($users))
                Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
              @else
                Menampilkan 1–6 dari 6 pengguna
              @endif
            </span>
            <nav class="pg-pages" aria-label="Halaman">
              @if (isset($users))
                @if ($users->onFirstPage())
                  <span class="pg-page is-disabled" aria-disabled="true">&lsaquo;</span>
                @else
                  <a href="{{ $users->previousPageUrl() }}" class="pg-page" aria-label="Sebelumnya">&lsaquo;</a>
                @endif
                @foreach (range(1, $users->lastPage()) as $halaman)
                  <a href="{{ $users->url($halaman) }}" class="pg-page {{ $halaman === $users->currentPage() ? 'is-active' : '' }}">{{ $halaman }}</a>
                @endforeach
                @if ($users->hasMorePages())
                  <a href="{{ $users->nextPageUrl() }}" class="pg-page" aria-label="Berikutnya">&rsaquo;</a>
                @else
                  <span class="pg-page is-disabled" aria-disabled="true">&rsaquo;</span>
                @endif
              @else
                <span class="pg-page is-disabled" aria-disabled="true">&lsaquo;</span>
                <span class="pg-page is-active">1</span>
                <span class="pg-page is-disabled" aria-disabled="true">&rsaquo;</span>
              @endif
            </nav>
          </div>
        </section>
      </main>
    </div>
  </div>

  <!-- Panel detail pengguna: riwayat pesanan (terbuka dari tombol mata) -->
  <div class="drawer-overlay" id="drawer-overlay" hidden></div>
  <aside class="drawer" id="drawer" role="dialog" aria-modal="true" aria-labelledby="drawer-judul" hidden>
    <div class="drawer-head">
      <h2 class="drawer-id" id="drawer-judul">Nama pengguna</h2>
      <button type="button" class="drawer-x" id="drawer-x" aria-label="Tutup">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>

    <div class="drawer-form">
      <div class="drawer-body">
        <div class="pg-profil">
          <span class="avatar avatar-lg" id="drawer-avatar">NP</span>
          <div class="pg-profil-info">
            <p class="pg-profil-wa" id="drawer-wa"></p>
            <p class="pg-profil-gabung" id="drawer-gabung"></p>
          </div>
          <span class="badge" id="drawer-badge">Aktif</span>
        </div>

        <div class="pg-stats">
          <div class="pg-stat">
            <p class="pg-stat-label">Total pesanan</p>
            <p class="pg-stat-value" id="drawer-pesanan">0</p>
          </div>
          <div class="pg-stat">
            <p class="pg-stat-label">Total belanja</p>
            <p class="pg-stat-value" id="drawer-total">Rp 0</p>
          </div>
        </div>

        <h3 class="pg-riwayat-judul">Riwayat pesanan</h3>
        <ul class="pg-riwayat" id="drawer-riwayat"></ul>
        <p class="pg-riwayat-kosong" id="drawer-riwayat-kosong" hidden>Belum ada pesanan.</p>
      </div>

      <div class="drawer-foot">
        <button type="button" class="btn-batal" id="drawer-tutup">Tutup</button>
      </div>
    </div>
  </aside>

  <!-- Konfirmasi keluar -->
  <div class="modal-overlay" id="modal-logout" hidden>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-logout-title">
      <h2 class="modal-title" id="modal-logout-title">Keluar dari panel admin?</h2>
      <p class="modal-text">Kamu perlu masuk lagi untuk mengelola produk dan pesanan.</p>
      <form action="{{ route('logout') }}" method="POST" class="modal-actions">
        @csrf
        <button type="button" class="btn-batal" data-close-logout>Batal</button>
        <button type="submit" class="btn-ya">Ya, keluar</button>
      </form>
    </div>
  </div>

  <script src="{{ asset('js/dashboard.js') }}"></script>
  <script src="{{ asset('js/pengguna.js') }}"></script>
  <script src="{{ asset('js/transisi.js') }}"></script>
</body>
</html>
