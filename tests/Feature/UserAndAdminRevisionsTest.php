<?php

namespace Tests\Feature;

use App\Models\Basket;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAndAdminRevisionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 1. Status 'sedang_dipinjam' dapat disimpan dan transisi stok berjalan benar.
     */
    public function test_order_status_sedang_dipinjam_transition_and_stock()
    {
        $admin = User::create([
            'nama_lengkap' => 'Admin Cabil',
            'username'     => 'admin_test_1',
            'email'        => 'admin1@cabilrent.id',
            'no_telp'      => '081234567891',
            'password'     => bcrypt('secret'),
            'role'         => 'admin',
            'status'       => 'aktif',
        ]);

        $category = Category::create([
            'nama_kategori' => 'Kebaya Wisuda',
            'slug'          => 'kebaya-wisuda',
        ]);

        $product = Product::create([
            'kategori_id'   => $category->id,
            'nama_produk'   => 'Kebaya Kartini Soft Pink',
            'slug'          => 'kebaya-kartini-soft-pink',
            'foto_produk'   => 'pink.jpg',
            'harga_sewa'    => 150000,
            'stok'          => 3,
            'status_produk' => 'tersedia',
        ]);

        $customer = User::create([
            'nama_lengkap' => 'Customer Cabil',
            'username'     => 'cust_test_1',
            'email'        => 'cust1@cabilrent.id',
            'no_telp'      => '081234567890',
            'password'     => bcrypt('secret'),
            'role'         => 'customers',
            'status'       => 'aktif',
        ]);

        $basket = Basket::create([
            'user_id'       => $customer->id,
            'produk_id'     => $product->id,
            'jumlah'        => 1,
            'tanggal_acara' => now()->addDays(5)->toDateString(),
        ]);

        $order = Order::create([
            'kode_pesanan'   => 'CR-TEST-001',
            'basket_id'      => $basket->id,
            'no_whatsapp'    => '081234567890',
            'domisili'       => 'Banjarmasin',
            'instagram'      => '@cabil',
            'tb_bb'          => '160 cm / 48 kg',
            'desk_pemesanan' => 'Kebaya Kartini Soft Pink',
            'total_harga'    => 250000,
            'status_pesan'   => 'pending',
        ]);

        // Transisi 1: pending -> dikonfirmasi (stok berkurang 1: 3 -> 2)
        $this->actingAs($admin)
            ->patch(route('pesanan.status', $order->id), [
                'status_pesan' => 'dikonfirmasi',
            ])
            ->assertRedirect();

        $this->assertEquals(2, $product->fresh()->stok);
        $this->assertEquals('dikonfirmasi', $order->fresh()->status_pesan);

        // Transisi 2: dikonfirmasi -> sedang_dipinjam (stok tetap 2 karena barang masih dipinjam)
        $this->actingAs($admin)
            ->patch(route('pesanan.status', $order->id), [
                'status_pesan' => 'sedang_dipinjam',
            ])
            ->assertRedirect();

        $this->assertEquals(2, $product->fresh()->stok);
        $this->assertEquals('sedang_dipinjam', $order->fresh()->status_pesan);

        // Transisi 3: sedang_dipinjam -> selesai (stok dipulihkan kembali ke 3)
        $this->actingAs($admin)
            ->patch(route('pesanan.status', $order->id), [
                'status_pesan' => 'selesai',
            ])
            ->assertRedirect();

        $this->assertEquals(3, $product->fresh()->stok);
        $this->assertEquals('selesai', $order->fresh()->status_pesan);
    }

    /**
     * 2. Filter Pesanan admin dapat memfilter berdasarkan status dan tanggal.
     */
    public function test_admin_pesanan_filters_by_status_and_date()
    {
        $admin = User::create([
            'nama_lengkap' => 'Admin Cabil 2',
            'username'     => 'admin_test_2',
            'email'        => 'admin2@cabilrent.id',
            'no_telp'      => '081234567892',
            'password'     => bcrypt('secret'),
            'role'         => 'admin',
            'status'       => 'aktif',
        ]);

        $category = Category::create([
            'nama_kategori' => 'Kebaya Modern',
            'slug'          => 'kebaya-modern',
        ]);

        $product = Product::create([
            'kategori_id'   => $category->id,
            'nama_produk'   => 'Kebaya Brokat Silver',
            'slug'          => 'kebaya-brokat-silver',
            'foto_produk'   => 'silver.jpg',
            'harga_sewa'    => 200000,
            'stok'          => 2,
            'status_produk' => 'tersedia',
        ]);

        $basket1 = Basket::create([
            'user_id'       => $admin->id,
            'produk_id'     => $product->id,
            'jumlah'        => 1,
            'tanggal_acara' => now()->addDays(3)->toDateString(),
        ]);

        $orderPending = Order::create([
            'kode_pesanan'   => 'CR-ORD-PENDING',
            'basket_id'      => $basket1->id,
            'no_whatsapp'    => '081234567891',
            'domisili'       => 'Banjarbaru',
            'tb_bb'          => '158 cm / 45 kg',
            'desk_pemesanan' => 'Kebaya Brokat Silver',
            'total_harga'    => 300000,
            'status_pesan'   => 'pending',
        ]);
        Order::where('id', $orderPending->id)->update(['created_at' => '2026-11-20 10:00:00']);

        $basket2 = Basket::create([
            'user_id'       => $admin->id,
            'produk_id'     => $product->id,
            'jumlah'        => 1,
            'tanggal_acara' => now()->addDays(4)->toDateString(),
        ]);

        $orderDipinjam = Order::create([
            'kode_pesanan'   => 'CR-ORD-PINJAM',
            'basket_id'      => $basket2->id,
            'no_whatsapp'    => '081234567892',
            'domisili'       => 'Banjarmasin',
            'tb_bb'          => '162 cm / 52 kg',
            'desk_pemesanan' => 'Kebaya Brokat Silver',
            'total_harga'    => 300000,
            'status_pesan'   => 'sedang_dipinjam',
        ]);
        Order::where('id', $orderDipinjam->id)->update(['created_at' => '2026-11-25 10:00:00']);

        // Filter berdasarkan status sedang_dipinjam
        $resFilterStatus = $this->actingAs($admin)->get('/pesanan?status=sedang_dipinjam');
        $resFilterStatus->assertStatus(200);
        $resFilterStatus->assertSee('CR-ORD-PINJAM');
        $resFilterStatus->assertDontSee('CR-ORD-PENDING');

        // Filter berdasarkan tanggal dari 2026-11-24
        $resFilterTanggal = $this->actingAs($admin)->get('/pesanan?tanggal_dari=2026-11-24');
        $resFilterTanggal->assertStatus(200);
        $resFilterTanggal->assertSee('CR-ORD-PINJAM');
        $resFilterTanggal->assertDontSee('CR-ORD-PENDING');
    }

    /**
     * 3. Halaman Detail tidak lagi menampilkan "Beli Langsung", melainkan "Sewa Langsung via WhatsApp".
     */
    public function test_detail_displays_sewa_langsung_instead_of_beli()
    {
        $category = Category::create([
            'nama_kategori' => 'Kebaya Wisuda',
            'slug'          => 'kebaya-wisuda-detail',
        ]);

        $product = Product::create([
            'kategori_id'   => $category->id,
            'nama_produk'   => 'Kebaya Anggun Lavender',
            'slug'          => 'kebaya-anggun-lavender',
            'foto_produk'   => 'lavender.jpg',
            'harga_sewa'    => 175000,
            'stok'          => 2,
            'status_produk' => 'tersedia',
        ]);

        $response = $this->get('/detail/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee('Sewa Langsung via WhatsApp');
        $response->assertDontSee('Beli Langsung via WhatsApp');
    }

    /**
     * 4. Halaman Kontak memuat bagian form pertanyaan & konsultasi yang natural dan tidak kaku/robotik.
     */
    public function test_contact_page_has_question_form_notice()
    {
        $response = $this->get('/kontak');
        $response->assertStatus(200);
        $response->assertSee('Tanya &amp; Konsultasi Busana', false);
        $response->assertSee('Kirim Pertanyaan via WhatsApp');
    }

    /**
     * 4b. Halaman detail produk, data pesanan, dan riwayat pesanan memiliki tombol kembali.
     */
    public function test_detail_pemesanan_and_riwayat_have_back_buttons()
    {
        $cat = Category::firstOrCreate(['slug' => 'kebaya-wisuda'], ['nama_kategori' => 'Kebaya Wisuda']);
        $product = Product::create([
            'kategori_id'   => $cat->id,
            'nama_produk'   => 'Kebaya Soft Lavender',
            'slug'          => 'kebaya-soft-lavender-' . uniqid(),
            'foto_produk'   => 'lavender.jpg',
            'harga_sewa'    => 200000,
            'stok'          => 4,
            'status_produk' => 'tersedia',
        ]);

        // Halaman Detail Produk punya tombol Kembali ke Koleksi
        $resDetail = $this->get('/detail/' . $product->id);
        $resDetail->assertStatus(200);
        $resDetail->assertSee('Kembali ke Koleksi');
        $resDetail->assertSee(url('/koleksi'));

        $user = User::create([
            'nama_lengkap' => 'Rina Melati',
            'username'     => 'rina_melati',
            'email'        => 'rina@example.com',
            'no_telp'      => '081233445566',
            'password'     => bcrypt('secret'),
            'role'         => 'customers',
            'status'       => 'aktif',
        ]);

        // Halaman Data Pemesanan punya tombol Kembali ke Keranjang
        $resPemesanan = $this->actingAs($user)->get('/pemesanan');
        $resPemesanan->assertStatus(200);
        $resPemesanan->assertSee('Kembali ke Keranjang');
        $resPemesanan->assertSee(url('/keranjang'));

        // Halaman Riwayat Pesanan punya tombol Kembali ke Beranda dan footer seragam
        $resRiwayat = $this->actingAs($user)->get('/riwayat-pesanan');
        $resRiwayat->assertStatus(200);
        $resRiwayat->assertSee('Kembali ke Beranda');
        $resRiwayat->assertSee('Riwayat Pesanan Saya');
        $resRiwayat->assertSee('footer-section');
    }

    /**
     * 5. Penanda status login di Navbar dan halaman Pemesanan.
     * Pelanggan belum login tidak bisa mengakses form pemesanan dan diarahkan login terlebih dahulu.
     */
    public function test_navbar_and_order_page_show_login_status_indicator()
    {
        // Kondisi 1: Tamu (Belum Login) mencoba akses /pemesanan dialihkan ke login
        $resGuest = $this->get('/pemesanan');
        $resGuest->assertRedirect(route('login'));
        $resGuest->assertSessionHas('warning', 'Silakan masuk (login) terlebih dahulu untuk melanjutkan pemesanan sewa.');

        // Cek halaman publik (misal /koleksi) menampilkan status Belum Login di navbar
        $resKoleksi = $this->get('/koleksi');
        $resKoleksi->assertStatus(200);
        $resKoleksi->assertSee('Belum Login');

        // Kondisi 2: Pelanggan Login dapat mengakses /pemesanan dengan status login & info lengkap
        $user = User::create([
            'nama_lengkap' => 'Siti Aisyah',
            'username'     => 'siti_aisyah',
            'email'        => 'siti@example.com',
            'no_telp'      => '081298765432',
            'password'     => bcrypt('secret'),
            'role'         => 'customers',
            'status'       => 'aktif',
        ]);

        $resAuth = $this->actingAs($user)->get('/pemesanan');
        $resAuth->assertStatus(200);
        $resAuth->assertSee('Sedang Login');
        $resAuth->assertSee('Siti Aisyah');
        $resAuth->assertSee('Wajib diisi');
        $resAuth->assertSee('Opsional');
    }

    /**
     * 6. Tamu belum login tidak bisa melakukan checkout dan harus login terlebih dahulu.
     */
    public function test_guest_cannot_checkout_and_must_login_first()
    {
        $category = Category::firstOrCreate(['slug' => 'kebaya-wisuda'], ['nama_kategori' => 'Kebaya Wisuda']);
        $product = Product::create([
            'kategori_id'   => $category->id,
            'nama_produk'   => 'Kebaya Brokat Silver Test',
            'slug'          => 'kebaya-brokat-silver-test-' . uniqid(),
            'foto_produk'   => 'silver.jpg',
            'harga_sewa'    => 200000,
            'stok'          => 5,
            'status_produk' => 'tersedia',
        ]);

        // Request JSON dari frontend saat belum login
        $resJson = $this->postJson(route('order.checkout'), [
            'nama_lengkap'  => 'Tamu Cabil',
            'no_whatsapp'   => '081234567890',
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160 cm / 50 kg',
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => [
                ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
            ]
        ]);

        $resJson->assertStatus(401);
        $resJson->assertJson([
            'success'  => false,
            'redirect' => route('login'),
        ]);

        // Request POST standar dari guest dialihkan ke login
        $resWeb = $this->post(route('order.checkout'), [
            'nama_lengkap'  => 'Tamu Cabil',
            'no_whatsapp'   => '081234567890',
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160 cm / 50 kg',
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([
                ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
            ])
        ]);

        $resWeb->assertRedirect(route('login'));
        $resWeb->assertSessionHas('warning');
    }

    /**
     * 7. Tamu belum login tidak bisa menambahkan produk ke keranjang dan harus login terlebih dahulu.
     */
    public function test_guest_cannot_add_item_to_cart_and_must_login_first()
    {
        $category = Category::firstOrCreate(['slug' => 'kebaya-wisuda'], ['nama_kategori' => 'Kebaya Wisuda']);
        $product = Product::create([
            'kategori_id'   => $category->id,
            'nama_produk'   => 'Kebaya Modern Brokat Maroon',
            'slug'          => 'kebaya-modern-brokat-maroon-' . uniqid(),
            'foto_produk'   => 'maroon.jpg',
            'harga_sewa'    => 250000,
            'stok'          => 3,
            'status_produk' => 'tersedia',
        ]);

        // Request JSON dari frontend saat belum login
        $resJson = $this->postJson(route('cart.add'), [
            'produk_id' => $product->id,
            'jumlah'    => 1,
        ]);

        $resJson->assertStatus(401);
        $resJson->assertJson([
            'success'  => false,
            'redirect' => route('login'),
        ]);

        // Request POST standar dari guest dialihkan ke login
        $resWeb = $this->post(route('cart.add'), [
            'produk_id' => $product->id,
            'jumlah'    => 1,
        ]);

        $resWeb->assertRedirect(route('login'));
        $resWeb->assertSessionHas('warning');
        $this->assertEquals(0, Basket::count());
    }

    /**
     * 8. Halaman Login memuat tombol Kembali ke Beranda (Home) ketika user diarahkan login.
     */
    public function test_login_page_has_back_button_to_home_page()
    {
        // Akses langsung ke halaman login
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Kembali ke Beranda');
        $response->assertSee(url('/'));

        // Akses halaman login karena dialihkan setelah mencoba pesan
        $responseRedirect = $this->get('/pemesanan');
        $responseRedirect->assertRedirect(route('login'));

        $responseFollowed = $this->followRedirects($responseRedirect);
        $responseFollowed->assertStatus(200);
        $responseFollowed->assertSee('Kembali ke Beranda');
        $responseFollowed->assertSee('Atau kembali ke Beranda (Home)');
    }
}
