<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kategori - Cabil.Rent</title>

  <!-- Tata letak admin (sidebar, bar atas, kartu, badge) dipakai bersama dashboard -->
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <!-- CSS khusus halaman Kategori -->
  <link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
  <link rel="stylesheet" href="{{ asset('css/transisi.css') }}">
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
          <a href="{{ url('/kategori') }}" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
              <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            <span>Kategori</span>
          </a>

          <p class="nav-section">PENGGUNA</p>
          <a href="{{ url('/pengguna') }}" class="nav-item">
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
            <path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
            <line x1="7" y1="7" x2="7.01" y2="7"></line>
          </svg>
          Kategori
        </h1>

        @if (session('success'))
          <p class="flash-success">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
          <p class="flash-error">{{ $errors->first() }}</p>
        @endif

        <!-- Kartu kategori -->
        <section class="kt-grid">
          @php $totalProduk = $categories->sum('products_count'); @endphp
          @forelse ($categories as $cat)
            @php
              $namaTampil = str_ireplace('kemben', 'Kamisol', $cat->nama_kategori); // BACKEND: ganti nama di database lalu hapus baris ini
              $slug = $cat->slug ?: \Illuminate\Support\Str::slug($cat->nama_kategori);
              $gambar = match ($slug) {
                  'heels' => asset('images/koleksi/heels-arin.png'),
                  'kamisol', 'kemben' => asset('images/koleksi/kamisol-m.png'),
                  default => asset('images/koleksi/kebaya-coco.png'),
              };
            @endphp
            <article class="kt-card">
              <div class="kt-foto">
                <img src="{{ $gambar }}" alt="{{ $namaTampil }}" loading="lazy" decoding="async">
              </div>

              <div class="kt-info">
                <h2 class="kt-nama">{{ $namaTampil }}</h2>
                <!-- BACKEND: tampil jika tabel categories punya kolom deskripsi -->
                <p class="kt-desk">{{ $cat->deskripsi ?? '' }}</p>

                <div class="kt-bar"><span class="kt-bar-isi" style="width: {{ $totalProduk > 0 ? round($cat->products_count / $totalProduk * 100) : 0 }}%"></span></div>

                <p class="kt-jumlah">{{ $cat->products_count }} produk</p>

                <div class="kt-aksi">
                  <a href="{{ url('/produk') }}?kategori={{ $cat->id }}" class="kt-lihat">Lihat produk &rarr;</a>

                  <div class="kt-icons">
                    <!-- Ubah: membuka kotak dialog berisi data kategori (js/kategori.js membaca data-*) -->
                    <button type="button" class="kt-icon btn-ubah" title="Ubah"
                            data-action="{{ url('/kategori/' . $cat->id) }}"
                            data-nama="{{ $namaTampil }}"
                            data-deskripsi="{{ $cat->deskripsi ?? '' }}">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                      </svg>
                    </button>

                    <form action="{{ route('kategori.destroy', $cat->id) }}" method="POST"
                          data-konfirmasi="Hapus kategori {{ $namaTampil }}?">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="kt-icon" title="Hapus">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                          <path d="M10 11v6"></path>
                          <path d="M14 11v6"></path>
                          <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
                        </svg>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </article>
          @empty
            <p class="kt-kosong">Belum ada kategori.</p>
          @endforelse
        </section>

        <!-- Tambah kategori baru (di bagian bawah, sesuai desain) -->
        <button type="button" class="kt-tambah" id="btn-tambah">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
            <line x1="12" y1="4" x2="12" y2="20"></line>
            <line x1="4" y1="12" x2="20" y2="12"></line>
          </svg>
          <span class="kt-tambah-teks">
            <span class="kt-tambah-judul">Tambah kategori baru</span>
            <span class="kt-tambah-ket">Beri nama dan deskripsi untuk mengelompokkan koleksi berikutnya.</span>
          </span>
        </button>
      </main>
    </div>
  </div>

  <!-- Kotak dialog Tambah / Ubah Kategori (satu form untuk keduanya, diatur js/kategori.js) -->
  <div class="modal-overlay" id="modal-kategori" hidden>
    <div class="modal kt-modal" role="dialog" aria-modal="true" aria-labelledby="modal-kategori-judul">
      <h2 class="modal-title" id="modal-kategori-judul">Tambah Kategori</h2>

      <form id="form-kategori" method="POST" action="{{ route('kategori.store') }}"
            data-action-tambah="{{ route('kategori.store') }}">
        @csrf
        <!-- Aktif hanya saat mode Ubah (BACKEND: route PUT /kategori/{id}) -->
        <input type="hidden" name="_method" value="PUT" id="field-method" disabled>

        <div class="kt-field">
          <label for="field-nama">Nama kategori</label>
          <input type="text" name="nama_kategori" id="field-nama" maxlength="50" required>
        </div>

        <div class="kt-field">
          <label for="field-deskripsi">Deskripsi singkat</label>
          <textarea name="deskripsi" id="field-deskripsi" rows="3"></textarea>
        </div>

        <div class="modal-actions">
          <button type="button" class="btn-batal" id="kt-batal">Batal</button>
          <button type="submit" class="btn-ya">Simpan</button>
        </div>
      </form>
    </div>
  </div>

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
  <script src="{{ asset('js/kategori.js') }}"></script>
  <script src="{{ asset('js/transisi.js') }}"></script>
</body>
</html>
