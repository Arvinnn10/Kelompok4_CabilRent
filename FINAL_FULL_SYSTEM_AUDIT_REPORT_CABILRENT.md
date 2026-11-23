# LAPORAN FINAL FULL SYSTEM AUDIT & DEEP CODE AUDIT — CABILRENT

**Project:** CabilRent — Sistem Informasi Persewaan Kebaya, Heels, dan Kamisol  
**Framework:** Laravel 12.69.2 (PHP 8.3.32)  
**Database:** MySQL 8.0.30 (`127.0.0.1:3316`, DB: `cabil_rent`)  
**Sifat Pemeriksaan:** **READ-ONLY AUDIT (Strictly No Code/Database Modification)**  
**Waktu Audit:** 23 November 2026  

---

## 1. RINGKASAN AUDIT

| Metrik Audit | Jumlah | Keterangan |
|---|---|---|
| **Total File yang Diperiksa** | **108 file** | 58 file PHP + 21 file Blade Views + 15 file CSS + 14 file JavaScript |
| **Total Folder yang Diperiksa** | **14 folder** | `app/Http/Controllers`, `app/Models`, `app/Http/Middleware`, `routes`, `database/migrations`, `database/seeders`, `resources/views`, `public/js`, `public/css`, `public/images`, `config`, `bootstrap`, `tests/Unit`, `tests/Feature` |
| **Total Fitur yang Diperiksa** | **16 fitur utama** | Auth Admin & Customer, OAuth Google, Katalog, Pencarian & Filter, Autocomplete Kategori, Detail Produk, Keranjang Belanja, Checkout Multi-Item, Pembuatan Invoice WhatsApp, Riwayat Pesanan, Admin Dashboard & Donut Chart, Manajemen Pesanan & Status Stok, Manajemen Produk, Manajemen Kategori, Manajemen Pengguna & Pemblokiran, Cache Busting & Dev Hot Reload |
| **Total Bug Ditemukan** | **2 bug minor** | Typo asset path pada partial yang tidak aktif & form kontak client-side tanpa CSRF |
| **Total Error Ditemukan** | **0 error kritis** | Tidak ditemukan PHP syntax error, Blade compilation error, maupun runtime exception |
| **Total Automated Tests** | **33 test case** | 186 assertions |
| **Hasil Automated Tests** | **33 PASS, 0 FAIL, 0 SKIPPED** | Durasi pengujian: 2,52 detik (100% lulus) |

---

## 2. DAFTAR TEMUAN BUG

### [BUG-MEDIA-001] Typo Path Asset Icon Instagram pada Footer Partial
- **Kategori:** Frontend / Blade Asset Reference
- **Tingkat Prioritas:** **Low**
- **Lokasi File:** `resources/views/layouts/partials/footer.blade.php` (Baris 12)
- **Penyebab:** Pada file partial `footer.blade.php`, tag gambar media sosial memanggil `asset('images/ig.png')`. Berkas fisik yang tersedia di direktori `public/images/` adalah `instagram.png`.
- **Dampak terhadap Website:** Berkas `resources/views/layouts/partials/footer.blade.php` saat ini **bersifat mandiri dan tidak di-`@include` oleh halaman aktif manapun** (seluruh 7 halaman customer utama seperti `home.blade.php` dan `koleksi.blade.php` menyertakan footer langsung secara inline menggunakan `asset('images/instagram.png')`). Namun, jika suatu saat pengembang baru meng-`@include` partial tersebut, ikon Instagram akan tampak sebagai gambar rusak (*broken image*).
- **Langkah Mereproduksi:**
  1. Buka berkas `resources/views/layouts/partials/footer.blade.php`.
  2. Periksa baris 12: `<img src="{{ asset('images/ig.png') }}" alt="Instagram">`.
  3. Periksa isi direktori `public/images/`, berkas `ig.png` tidak ditemukan (yang ada adalah `instagram.png`).
- **Bukti Error:**
  `asset('images/ig.png') NOT FOUND in public/images!`

---

### [BUG-FORM-001] Form Kontak Menggunakan `method="POST"` Tanpa Direktif `@csrf`
- **Kategori:** Form Security / CSRF
- **Tingkat Prioritas:** **Low**
- **Lokasi File:** `resources/views/kontak.blade.php` (Baris 116)
- **Penyebab:** Elemen `<form class="kontak-form" id="kontak-form" action="#" method="POST" data-wa="...">` tidak menyertakan token `@csrf`.
- **Dampak terhadap Website:** Secara fungsional saat ini, interaksi form dicegat (*intercepted*) oleh `public/js/kontak.js` melalui `e.preventDefault()`, lalu dirangkum menjadi link `https://wa.me/...` untuk membuka aplikasi WhatsApp. Namun, jika pengguna menonaktifkan JavaScript pada browser atau script gagal dimuat, pengiriman form metode `POST` ke route `#` tanpa CSRF token berpotensi memicu respon `419 Page Expired`.
- **Langkah Mereproduksi:**
  1. Buka `resources/views/kontak.blade.php` baris 116.
  2. Amati atribut form: `action="#" method="POST"`.
  3. Tidak terdapat `@csrf` di dalam elemen body `<form>`.
- **Bukti:**
  `kontak.blade.php: MISSING_CSRF_IN_FORM`

---

### [FINDING-DB-001] Temuan Data Historis Keranjang Duplikat Lawas di Database MySQL
- **Kategori:** Database Historical Data
- **Tingkat Prioritas:** **Low**
- **Lokasi:** Tabel `baskets` (MySQL `cabil_rent`)
- **Penyebab:** Pada tanggal 20 November 2026 pukul 23:25 (sebelum implementasi perbaikan pencegahan keranjang duplikat `BUG-005`), akun pengguna ID 3 menambahkan produk ID 2 sebanyak 6 kali berturut-turut dalam hitungan detik, menghasilkan baris keranjang duplikat pada `id` 4, 5, 6, 7, 8, dan 9.
- **Dampak terhadap Website:** Tidak menimbulkan crash. Logika baru di `CartController::addItem` dan `OrderController::checkout` saat ini sudah menerapkan perlindungan akumulasi jumlah (`$basket->increment('jumlah', $jumlah)`) serta `whereDoesntHave('orders')`. Data lama ini aman namun disarankan untuk dirapikan secara berkala.
- **Bukti:**
  `SELECT user_id, produk_id, count(*) FROM baskets WHERE id NOT IN (SELECT basket_id FROM orders) GROUP BY user_id, produk_id HAVING count(*) > 1` menghasilkan 6 record duplikat lawas milik user ID 3.

---

## 3. FITUR YANG BERHASIL DIPERIKSA (SEMUA BERFUNGSI DENGAN BAIK)

1. **Autentikasi & Otorisasi:**
   - **Login Admin:** Terarah ke `/dashboard` melalui middleware `EnsureAdmin`.
   - **Login Customer:** Terarah ke `/` (beranda).
   - **Registrasi:** Validasi konfirmasi password bekerja, nilai `role` dikunci permanen ke `'customers'` (mencegah eskalasi hak akses).
   - **Logout:** Sesi di-`invalidate()`, token CSRF di-`regenerate()`, terarah ke `/login` disertai flash message.
   - **Pemblokiran Pengguna:** Akun berstatus `diblokir` ditolak saat login. Jika diblokir saat sesi aktif, middleware `EnsureUserNotBlocked` membatalkan sesi secara *real-time* dan mengembalikan HTTP 403 / redirect login.
   - **Proteksi Akses:** Customer atau tamu yang mencoba membuka `/dashboard`, `/pesanan`, `/produk`, `/kategori`, `/pengguna` ditolak dan dialihkan.

2. **Katalog & Detail Produk:**
   - Halaman `/koleksi` memuat produk aktif dari database (`status_produk = 'tersedia'`).
   - Filter kategori bekerja berdasarkan `slug` dan nama kategori.
   - Fitur pencarian (`search`, `cari`, `q`) menyaring nama produk dan deskripsi.
   - Pengurutan bekerja (`terbaru`, `harga-terendah`, `harga-tertinggi`, dan `populer` berbasis `withCount('baskets')`).
   - Halaman `/detail/{id}` menampilkan informasi busana, harga sewa, ukuran, stok, dan 4 produk terkait dalam kategori yang sama. Memiliki fallback aman jika ID tidak ditemukan.
   - Endpoint `/api/kategori` mengembalikan JSON data kategori disertai header `Cache-Control: public, max-age=3600` untuk performa tinggi.

3. **Keranjang Belanja & Checkout Multi-Item:**
   - Penambahan produk ke keranjang (`/keranjang/tambah`) mengelompokkan item yang sama dan menambah `jumlah` (mencegah baris ganda).
   - Validasi `jumlah >= 1` ditegakkan pada tingkat Controller, JavaScript, Model Hook (`saving`), dan MySQL CHECK Constraint (`chk_baskets_jumlah`).
   - Checkout multi-item (`/pesanan/checkout`) berjalan di dalam database transaction (`DB::beginTransaction()`). Seluruh produk dalam satu keranjang belanja mendapatkan **satu `kode_pesanan` unik yang sama** (format `CR-INV-YYYYMMDD-XXXXXX`).
   - Pesan WhatsApp otomatis disusun lengkap dengan rincian nomor invoice, data diri penyewa, daftar busana, subtotal sewa, biaya deposit (Rp 100.000/item), dan total tagihan awal.
   - Halaman `/riwayat-pesanan` mengelompokkan pesanan customer berdasarkan `kode_pesanan` secara rapi.

4. **Admin Dashboard, Pesanan, Produk, Kategori, & Pengguna:**
   - **Dashboard:** Statistik metrik (Total Produk, Stok Tersedia, Kategori, Sewa Berjalan, Pendapatan) murni berasal dari agregasi database. Donut chart SVG dihitung dinamis dari status pesanan riil.
   - **Pesanan:** Filter status dan pencarian pesanan berfungsi. Perubahan status menjadi `dikonfirmasi` otomatis memotong stok produk; jika pesanan `dibatalkan` atau `selesai`, stok dikembalikan otomatis.
   - **Produk:** CRUD produk bekerja dengan pembuatan slug unik otomatis, sanitasi berkas gambar unggahan, dan proteksi penghapusan produk yang memiliki riwayat pesanan (diubah status menjadi `tidak_tersedia`).
   - **Kategori:** CRUD kategori bekerja dengan proteksi penghapusan jika kategori masih memiliki produk aktif.
   - **Pengguna:** Menampilkan daftar customer beserta jumlah transaksi dan total belanja. Fitur blokir dilengkapi pengaman: Admin tidak dapat memblokir dirinya sendiri maupun admin lainnya.

---

## 4. FITUR YANG GAGAL

- **NIL (Tidak Ada):** Tidak ada fitur fungsional yang gagal atau memicu error dalam pengujian.

---

## 5. FITUR YANG BELUM DAPAT DIUJI

- **Google OAuth Live Token Exchange dengan Server Google:**
  - *Alasan Teknis:* Controller telah mengimplementasikan penanganan OAuth (`redirectToGoogle`, `handleGoogleCallback`) dan skema database telah memuat kolom `google_id` dan `avatar`. Namun, pengujian pertukaran token riil membutuhkan kredensial Google Client Secret yang aktif dan tersambung ke domain live di Google Cloud Console. Pengujian simulasi lokal (`GoogleAuthTest`) telah dilakukan dengan 5 test case yang semuanya lulus.

---

## 6. HASIL DATABASE INTEGRITY AUDIT

| Aspek Database | Status | Keterangan |
|---|---|---|
| **Kesesuaian Skema Aktual** | **100% Sesuai** | Seluruh 6 tabel (`users`, `categories`, `products`, `baskets`, `orders`, `migrations`) sinkron dengan model dan migrasi. |
| **Penyelarasan Kolom Terakhir** | **100% Sesuai** | `orders.no_whatsapp` (VARCHAR 20), `orders.tb_bb` (VARCHAR 30), `orders.desk_pemesanan` (VARCHAR 150), `orders.kode_pesanan` (VARCHAR 50). |
| **Kolom `foto_kebaya`** | **Dipertahankan** | Kolom `products.foto_kebaya` tetap aman di database dan model. |
| **Primary Keys** | **100% Konsisten** | Seluruh tabel menggunakan `BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`. |
| **Foreign Keys & Rules** | **100% Konsisten** | `baskets.user_id` (CASCADE), `baskets.produk_id` (CASCADE), `products.kategori_id` (RESTRICT), `orders.basket_id` (RESTRICT). |
| **Check Constraints** | **Aktif di MySQL** | `chk_products_stok` (`stok >= 0`), `chk_baskets_jumlah` (`jumlah >= 1`). |
| **Orphan Records** | **0 Orphan** | Tidak ada record tanpa relasi induk (`user_id`, `produk_id`, `kategori_id`, `basket_id` valid). |
| **Keamanan Data Lama** | **100% Terjaga** | 7 user, 3 kategori, 16 produk, 14 keranjang, 5 order bawaan tetap utuh. |

---

## 7. HASIL SECURITY AUDIT

1. **Proteksi Injeksi SQL:**
   - Seluruh query menggunakan Laravel Eloquent ORM dan PDO Prepared Statements dengan parameter binding (`?`).
   - Tidak ditemukan fungsi `DB::raw()` atau `whereRaw()` yang menggabungkan input pengguna secara langsung.
2. **Proteksi Cross-Site Scripting (XSS):**
   - Seluruh variabel pada Blade views ditampilkan menggunakan sintaks auto-escaping `{{ $variabel }}`.
   - Hasil pemindaian terhadap sintaks raw `{!! ... !!}` menghasilkan **0 kemunculan** di seluruh 21 file Blade.
3. **Proteksi Cross-Site Request Forgery (CSRF):**
   - Seluruh form backend memiliki token `@csrf`.
   - Endpoint logout dan aksi manipulasi data sensitif terlindungi token CSRF.
4. **Proteksi Insecure Direct Object Reference (IDOR):**
   - Item keranjang dibatasi ketat oleh klausa `where('user_id', Auth::id())`.
   - Riwayat pesanan pelanggan dibatasi ketat oleh relasi `whereHas('basket', fn($q) => $q->where('user_id', Auth::id()))`.
5. **Proteksi File Upload:**
   - Validasi ketat pada `ProductController`: tipe file gambar (`jpeg, png, jpg, webp`), ukuran maksimal 2 MB, dan penamaan file acak unik (`time() . '_' . Str::random(12)`).

---

## 8. HASIL AUTOMATED TESTING

Hasil eksekusi penuh perintah `php artisan test`:
```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                                                            0.01s  

   PASS  Tests\Feature\CabilRentAuditFixesTest
  ✓ koleksi catalog displays database products                                                                   0.52s  
  ✓ cart duplicate row prevention increments existing quantity                                                   0.04s  
  ✓ checkout multi item shares same kode pesanan                                                                 0.03s  
  ✓ blocked user is logged out immediately by middleware                                                         0.03s  
  ✓ admin cannot block self or other admins                                                                      0.02s  
  ✓ delete product with orders safely marks as tidak tersedia                                                    0.03s  
  ✓ register requires password confirmation                                                                      0.03s  
  ✓ customer history page loads without variable error                                                           0.02s  
  ✓ navbar account button behavior                                                                               0.02s  
  ✓ admin cart removed and customer cart preserved                                                               0.05s  
  ✓ admin logout flow and session invalidation                                                                   0.05s  
  ✓ api kategori has cache headers                                                                               0.02s  

   PASS  Tests\Feature\CabilRentDatabaseFixesTest
  ✓ whatsapp number exceeding 20 chars fails validation                                                          0.02s  
  ✓ whatsapp number within 20 chars passes validation                                                            0.03s  
  ✓ tb bb exceeding 30 chars fails validation                                                                    0.02s  
  ✓ tb bb up to 30 chars saves successfully                                                                      0.03s  
  ✓ long product name saved in desk pemesanan without truncation                                                 0.02s  
  ✓ multi item checkout shares single kode pesanan                                                               0.03s  
  ✓ excel database consistency                                                                                   0.75s  

   PASS  Tests\Feature\CssHotReloadAndCacheBustingTest
  ✓ asset versioned appends filemtime query parameter                                                            0.02s  
  ✓ css dev status endpoint returns json in local environment                                                    0.03s  
  ✓ css hot reload middleware injects script in local html response                                              0.02s  
  ✓ customer pages render with versioned stylesheets                                                             0.04s  
  ✓ admin pages render with versioned stylesheets                                                                0.04s  
  ✓ production environment disables hot reload and preserves cache busting                                       0.03s  

   PASS  Tests\Feature\DatabaseStructureAndValidationTest
  ✓ basket jumlah cannot be zero or negative                                                                     0.05s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                0.02s  

   PASS  Tests\Feature\GoogleAuthTest
  ✓ redirects to login when google not configured                                                                0.03s  
  ✓ redirects to google when configured                                                                          0.03s  
  ✓ google callback creates new user and authenticates                                                           0.03s  
  ✓ google callback links existing user by email                                                                 0.04s  
  ✓ google callback handles exception gracefully                                                                 0.02s  

  Tests:    33 passed (186 assertions)
  Duration: 2.52s
```

---

## 9. KESIMPULAN AKHIR

Berdasarkan seluruh hasil pemeriksaan kode statis, audit relasi, uji keamanan, integritas basis data MySQL, dan 33 automated tests yang lulus 100%, status akhir sistem project CabilRent adalah:

### **STATUS: AMAN DENGAN CATATAN**

**Alasan Penetapan Status:**
1. **Aspek Fungsional & Kestabilan:** Seluruh alur utama (Autentikasi, Katalog, Keranjang, Checkout Multi-Item WhatsApp, Riwayat Pesanan, Admin Dashboard, Manajemen Pesanan, dan Manajemen Produk/Kategori/Pengguna) berjalan dengan sangat baik dan stabil tanpa runtime error.
2. **Aspek Keamanan:** Otentikasi dan otorisasi role terisolasi dengan ketat, CSRF aktif, input data diproteksi dari SQL Injection dan XSS, serta sanitasi file upload bekerja aman.
3. **Catatan Perbaikan Minor Mendatang (Non-Kritis):**
   - Menyelaraskan referensi nama berkas ikon `images/ig.png` menjadi `images/instagram.png` pada berkas mandiri `resources/views/layouts/partials/footer.blade.php`.
   - Menambahkan `@csrf` atau mengubah `method="POST"` menjadi `method="GET"` pada form kontak `resources/views/kontak.blade.php`.
   - Menjalankan pembersihan berkala (*maintenance cleaning*) terhadap baris data keranjang duplikat lawas tahun 2026 yang tersimpan sebelum rilis fitur pencegahan duplikasi.
