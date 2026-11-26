<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk - Cabil.Rent</title>

  <!-- Tata letak admin (sidebar, bar atas, kartu, badge) dipakai bersama dashboard -->
  <link rel="stylesheet" href="{{ asset_versioned('css/dashboard.css') }}">
  <!-- CSS khusus halaman Produk -->
  <link rel="stylesheet" href="{{ asset_versioned('css/produk.css') }}">
  <link rel="stylesheet" href="{{ asset_versioned('css/transisi.css') }}">

  <!-- Saat halaman dibuka tanpa pilihan kategori, langsung tampilkan kategori Pakaian Kebaya (atau kategori pertama) -->
  @php
    $kategoriAwal = $categories->first(function ($c) {
        return stripos($c->nama_kategori, 'kebaya') !== false;
    }) ?? $categories->first();
  @endphp
  @if ($kategoriAwal)
    <script>
      if (!new URLSearchParams(window.location.search).has('kategori')) {
        window.location.replace('{{ url('/produk') }}?kategori={{ $kategoriAwal->id }}');
      }
    </script>
  @endif
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
          <a href="{{ url('/produk') }}" class="nav-item active">
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

        <div class="topbar-actions" style="margin-left: auto;">
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
                <li class="notif-item is-unread">
                  <p class="notif-judul">Panel Produk</p>
                  <p class="notif-detail">Kelola stok dan katalog busana</p>
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
                <span style="font-size: 11px; color: #8c786c; font-weight: 600; text-transform: uppercase;">Administrator</span>
              </div>
            </div>
          </div>
        </div>
      </header>

      <main class="main-content">
        <h1 class="page-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
            <polyline points="3.3 7 12 12 20.7 7"></polyline>
            <line x1="12" y1="22" x2="12" y2="12"></line>
          </svg>
          Produk
        </h1>

        @if (session('success'))
          <p class="flash-success">{{ session('success') }}</p>
        @endif
        @if ($errors->any())
          <p class="flash-error">{{ $errors->first() }}</p>
        @endif

        <!-- Kartu ringkasan -->
        <section class="pr-stats">
          <div class="stat-card">
            <p class="pr-stat-label">Total produk</p>
            <p class="pr-stat-value">{{ $totalProducts ?? 0 }}</p>
          </div>
          <!-- BACKEND: "Stok menipis" dan "Stok habis" masih angka contoh dari desain -->
          <div class="stat-card">
            <p class="pr-stat-label">Stok menipis</p>
            <p class="pr-stat-value">2</p>
          </div>
          <div class="stat-card">
            <p class="pr-stat-label">Stok habis</p>
            <p class="pr-stat-value">0</p>
          </div>
          <div class="stat-card">
            <p class="pr-stat-label">Disembunyikan</p>
            <p class="pr-stat-value">{{ ($totalProducts ?? 0) - ($availableProducts ?? 0) }}</p>
          </div>
        </section>

        <!-- Cari, filter kategori (dropdown), dan tombol tambah -->
        <div class="pr-toolbar">
          <form method="GET" action="{{ url('/produk') }}" class="pr-filter-form">
            <div class="pr-search-box">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama busana/kebaya..." aria-label="Cari nama kebaya">
            </div>

            <div class="pr-category-filter">
              <label for="filter-kategori" class="filter-label">Kategori:</label>
              <select name="kategori" id="filter-kategori" class="pr-category-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                  <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nama_kategori }}
                  </option>
                @endforeach
              </select>
            </div>

            @if (request('kategori') || request('search'))
              <a href="{{ url('/produk') }}" class="btn-filter-reset" style="height:42px;padding:0 14px;display:inline-flex;align-items:center;background:#fce7e7;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;">Reset</a>
            @endif
          </form>

          <button type="button" class="btn-tambah" id="btn-tambah">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Produk
          </button>
        </div>

        <!-- Daftar produk -->
        <section class="pr-panel">
          <div class="pr-grid">
            @forelse ($products as $prod)
              @php
                $foto = $prod->foto_produk;
                if (!str_starts_with($foto, 'http')) {
                    $foto = asset('images/' . (str_starts_with($foto, 'koleksi/') || file_exists(public_path('images/' . $foto)) ? '' : 'koleksi/') . $foto);
                }
              @endphp
              <article class="pr-card">
                <div class="pr-foto">
                  <img src="{{ $foto }}" alt="{{ $prod->nama_produk }}" loading="lazy" decoding="async">
                </div>
                <div class="pr-info">
                  <h2 class="pr-nama">{{ $prod->nama_produk }}</h2>
                  <div class="pr-baris">
                    <span class="pr-harga">Rp {{ number_format($prod->harga_sewa, 0, ',', '.') }} / 3 hari</span>
                    <!-- BACKEND: tampil jika tabel products punya kolom stok -->
                    @isset($prod->stok)
                      <span class="pr-stok">{{ $prod->stok > 0 ? $prod->stok . ' pcs' : 'Habis' }}</span>
                    @endisset
                  </div>

                  <div class="pr-aksi">
                    <!-- Sakelar tampil/sembunyikan di website -->
                    <form action="{{ route('produk.toggle', $prod->id) }}" method="POST">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="pr-switch {{ $prod->status_produk === 'tersedia' ? 'is-on' : '' }}"
                              role="switch" aria-checked="{{ $prod->status_produk === 'tersedia' ? 'true' : 'false' }}"
                              title="{{ $prod->status_produk === 'tersedia' ? 'Tampil di website' : 'Disembunyikan' }}"></button>
                    </form>

                    <div class="pr-icons">
                      <!-- Ubah: membuka panel dengan data produk (js/produk.js membaca data-*) -->
                      <button type="button" class="pr-icon btn-ubah" title="Ubah"
                              data-action="{{ url('/produk/' . $prod->id) }}"
                              data-nama="{{ $prod->nama_produk }}"
                              data-kategori="{{ $prod->kategori_id }}"
                              data-ukuran="{{ $prod->ukuran ?? '' }}"
                              data-harga="{{ $prod->harga_sewa }}"
                              data-stok="{{ $prod->stok ?? 1 }}"
                              data-desk="{{ $prod->desk_produk }}"
                              data-tampil="{{ $prod->status_produk === 'tersedia' ? '1' : '0' }}"
                              data-foto="{{ $foto }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M12 20h9"></path>
                          <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                      </button>

                      <form action="{{ route('produk.destroy', $prod->id) }}" method="POST" data-konfirmasi="Hapus {{ $prod->nama_produk }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="pr-icon" title="Hapus">
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
              <p class="pr-kosong">Belum ada produk yang ditemukan.</p>
            @endforelse
          </div>

          <!-- Navigasi halaman -->
          <div class="pr-pagination">
            <nav class="pr-pages" aria-label="Halaman">
              @if ($products->onFirstPage())
                <span class="pr-page is-disabled" aria-disabled="true">&lsaquo;</span>
              @else
                <a href="{{ $products->previousPageUrl() }}" class="pr-page" aria-label="Sebelumnya">&lsaquo;</a>
              @endif

              @foreach (range(1, $products->lastPage()) as $halaman)
                <a href="{{ $products->url($halaman) }}" class="pr-page {{ $halaman === $products->currentPage() ? 'is-active' : '' }}">{{ $halaman }}</a>
              @endforeach

              @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="pr-page" aria-label="Berikutnya">&rsaquo;</a>
              @else
                <span class="pr-page is-disabled" aria-disabled="true">&rsaquo;</span>
              @endif
            </nav>
          </div>
        </section>
      </main>
    </div>
  </div>

  <!-- Panel Tambah / Ubah Produk (satu form dipakai untuk keduanya, diatur js/produk.js) -->
  <div class="drawer-overlay" id="drawer-overlay" hidden></div>
  <aside class="drawer" id="drawer" role="dialog" aria-modal="true" aria-labelledby="drawer-judul" hidden>
    <div class="drawer-head">
      <h2 class="drawer-id" id="drawer-judul">Tambah Produk</h2>
      <button type="button" class="drawer-x" id="drawer-x" aria-label="Tutup">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"></line>
          <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
      </button>
    </div>

    <form class="drawer-form" id="form-produk" method="POST" action="{{ route('produk.store') }}"
          data-action-tambah="{{ route('produk.store') }}" enctype="multipart/form-data">
      @csrf
      <!-- Aktif hanya saat mode Ubah (BACKEND: route PUT /produk/{id}) -->
      <input type="hidden" name="_method" value="PUT" id="field-method" disabled>

      <div class="drawer-body">
        <p style="font-size:12px;color:#786558;margin:0 0 16px 0;">
          <span style="color:#dc2626;font-weight:700;">*</span> Menandakan kolom <strong>wajib diisi</strong>. Kolom bertanda <em>(Opsional)</em> boleh dikosongkan.
        </p>

        <label class="pf-upload" for="field-foto">
          <img src="" alt="" class="pf-upload-img" id="pf-preview" hidden>
          <span class="pf-upload-teks" id="pf-upload-teks">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <rect x="3" y="3" width="18" height="18" rx="2"></rect>
              <circle cx="8.5" cy="8.5" r="1.5"></circle>
              <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
            Klik untuk unggah foto busana
            <small style="display:block;font-size:11px;color:#8c786c;margin-top:3px;">* (Wajib diisi saat menambah produk baru)</small>
          </span>
        </label>
        <input type="file" name="foto_produk" id="field-foto" accept="image/*" hidden>

        <div class="pf-field">
          <label for="field-nama">Nama produk <span style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi)</span></label>
          <input type="text" name="nama_produk" id="field-nama" maxlength="100" placeholder="Contoh: Kebaya Wisuda Brokat Peach" required>
        </div>

        <div class="pf-row">
          <div class="pf-field">
            <label for="field-kategori">Kategori <span style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi)</span></label>
            <select name="kategori_id" id="field-kategori" required>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
              @endforeach
            </select>
          </div>
          <div class="pf-field">
            <label for="field-ukuran">Ukuran <span style="color:#78716c;font-size:11px;font-weight:normal;">(Opsional / Tidak wajib)</span></label>
            <input type="text" name="ukuran" id="field-ukuran" maxlength="30" placeholder="Contoh: S, M, L, All Size">
          </div>
        </div>

        <div class="pf-row">
          <div class="pf-field">
            <label for="field-harga">Harga sewa / 3 hari (Rp) <span style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi)</span></label>
            <input type="number" name="harga_sewa" id="field-harga" min="0" placeholder="Contoh: 150000" required>
          </div>
          <div class="pf-field">
            <label for="field-stok">Stok <span style="color:#b91c1c;font-size:11px;font-weight:600;">* (Wajib diisi)</span></label>
            <input type="number" name="stok" id="field-stok" min="0" value="1" required>
          </div>
        </div>

        <div class="pf-field">
          <label for="field-desk">Deskripsi <span style="color:#78716c;font-size:11px;font-weight:normal;">(Opsional / Tidak wajib)</span></label>
          <textarea name="desk_produk" id="field-desk" rows="3" placeholder="Kelengkapan, bahan, warna, dll."></textarea>
        </div>

        <div class="pf-tampil">
          <div>
            <p class="pf-tampil-judul">Tampilkan di website <span style="color:#78716c;font-size:11px;font-weight:normal;">(Opsional)</span></p>
            <p class="pf-tampil-ket">Produk tersembunyi tidak terlihat pelanggan</p>
          </div>
          <!-- Nilai hidden dikirim bila sakelar mati; bila hidup, nilai "tersedia" menimpanya -->
          <input type="hidden" name="status_produk" value="tidak_tersedia">
          <label class="pf-toggle">
            <input type="checkbox" name="status_produk" value="tersedia" id="field-tampil" checked>
            <span class="pf-toggle-track"></span>
          </label>
        </div>
      </div>

      <div class="drawer-foot">
        <button type="button" class="btn-batal" id="drawer-batal">Batal</button>
        <button type="submit" class="btn-ya">Simpan</button>
      </div>
    </form>
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
  <script src="{{ asset('js/produk.js') }}"></script>
  <script src="{{ asset('js/transisi.js') }}"></script>
</body>
</html>
