<?php

namespace Tests\Feature;

use App\Models\Basket;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CabilRentAuditFixesTest extends TestCase
{
    use RefreshDatabase;

    private function createCustomer(array $overrides = []): User
    {
        return User::create(array_merge([
            'nama_lengkap' => 'Customer Test',
            'username'     => 'cust_' . uniqid(),
            'email'        => 'cust_' . uniqid() . '@example.com',
            'no_telp'      => '08' . rand(1000000000, 9999999999),
            'password'     => bcrypt('password123'),
            'role'         => 'customers',
            'status'       => 'aktif',
        ], $overrides));
    }

    private function createAdmin(array $overrides = []): User
    {
        return User::create(array_merge([
            'nama_lengkap' => 'Admin Test',
            'username'     => 'admin_' . uniqid(),
            'email'        => 'admin_' . uniqid() . '@example.com',
            'no_telp'      => '08' . rand(1000000000, 9999999999),
            'password'     => bcrypt('admin12345'),
            'role'         => 'admin',
            'status'       => 'aktif',
        ], $overrides));
    }

    private function createProduct(array $overrides = []): Product
    {
        $cat = Category::firstOrCreate(
            ['nama_kategori' => 'Kebaya Wisuda'],
            ['slug' => 'kebaya-wisuda']
        );

        return Product::create(array_merge([
            'kategori_id'   => $cat->id,
            'nama_produk'   => 'Kebaya Kartini Sage Green',
            'slug'          => 'kebaya-kartini-sage-green-' . uniqid(),
            'harga_sewa'    => 150000,
            'foto_produk'   => 'kebaya-sage.jpg',
            'status_produk' => 'tersedia',
            'stok'          => 3,
        ], $overrides));
    }

    /**
     * BUG-002: Dynamic Catalog displays products from database.
     */
    public function test_koleksi_catalog_displays_database_products(): void
    {
        $product = $this->createProduct(['nama_produk' => 'Kebaya Brokat Premium Cabil']);

        $response = $this->get('/koleksi');
        $response->assertStatus(200);
        $response->assertSee('Kebaya Brokat Premium Cabil');
    }

    /**
     * BUG-005: Cart duplicate prevention (increments quantity on repeat add).
     */
    public function test_cart_duplicate_row_prevention_increments_existing_quantity(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProduct(['stok' => 10]);
        $this->actingAs($customer);

        // Add 2 items first
        $res1 = $this->postJson(route('cart.add'), [
            'produk_id' => $product->id,
            'jumlah'    => 2,
        ]);
        $res1->assertStatus(200);

        // Add 3 more of the same item
        $res2 = $this->postJson(route('cart.add'), [
            'produk_id' => $product->id,
            'jumlah'    => 3,
        ]);
        $res2->assertStatus(200);

        // Verify only 1 basket row exists, with jumlah = 5
        $baskets = Basket::where('user_id', $customer->id)
            ->where('produk_id', $product->id)
            ->whereDoesntHave('orders')
            ->get();

        $this->assertCount(1, $baskets);
        $this->assertEquals(5, $baskets->first()->jumlah);
    }

    /**
     * BUG-007: Checkout multi-item groups orders under shared kode_pesanan.
     */
    public function test_checkout_multi_item_shares_same_kode_pesanan(): void
    {
        $customer = $this->createCustomer();
        $prod1 = $this->createProduct(['harga_sewa' => 100000]);
        $prod2 = $this->createProduct(['harga_sewa' => 150000]);
        $this->actingAs($customer);

        $checkoutData = [
            'nama_lengkap'  => 'Siti Rahma',
            'no_whatsapp'   => '081234567890',
            'domisili'      => 'Banjarmasin Selatan',
            'tb_bb'         => '160cm / 50kg',
            'tanggal_acara' => now()->addDays(2)->format('Y-m-d'),
            'items'         => json_encode([
                ['id' => $prod1->id, 'jumlah' => 1],
                ['id' => $prod2->id, 'jumlah' => 1],
            ]),
        ];

        $response = $this->postJson(route('order.checkout'), $checkoutData);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $orders = Order::whereHas('basket', fn($q) => $q->where('user_id', $customer->id))->get();
        $this->assertCount(2, $orders);

        $kodePesanan1 = $orders[0]->kode_pesanan;
        $kodePesanan2 = $orders[1]->kode_pesanan;

        $this->assertNotEmpty($kodePesanan1);
        $this->assertEquals($kodePesanan1, $kodePesanan2);
        $this->assertStringStartsWith('CR-INV-', $kodePesanan1);
    }

    /**
     * BUG-011: Real-time blocked user session invalidation middleware.
     */
    public function test_blocked_user_is_logged_out_immediately_by_middleware(): void
    {
        $user = $this->createCustomer(['status' => 'diblokir']);
        $this->actingAs($user);

        $response = $this->get('/riwayat-pesanan');
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }

    /**
     * BUG-012: Admin cannot block themselves or fellow admins.
     */
    public function test_admin_cannot_block_self_or_other_admins(): void
    {
        $admin1 = $this->createAdmin();
        $admin2 = $this->createAdmin();

        $this->actingAs($admin1);

        // Try blocking self
        $resSelf = $this->patch(route('pengguna.blokir', $admin1->id));
        $resSelf->assertSessionHas('error');
        $this->assertEquals('aktif', $admin1->fresh()->status);

        // Try blocking other admin
        $resOther = $this->patch(route('pengguna.blokir', $admin2->id));
        $resOther->assertSessionHas('error');
        $this->assertEquals('aktif', $admin2->fresh()->status);
    }

    /**
     * BUG-014: Deleting product with existing orders sets status to tidak_tersedia instead of 500 foreign key crash.
     */
    public function test_delete_product_with_orders_safely_marks_as_tidak_tersedia(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $product = $this->createProduct(['status_produk' => 'tersedia']);

        $basket = Basket::create([
            'user_id'       => $customer->id,
            'produk_id'     => $product->id,
            'jumlah'        => 1,
            'tanggal_acara' => now()->toDateString(),
        ]);

        Order::create([
            'basket_id'       => $basket->id,
            'kode_pesanan'    => 'CR-INV-TEST-001',
            'no_whatsapp'     => '081234567890',
            'domisili'        => 'Banjarmasin',
            'tb_bb'           => '160cm / 50kg',
            'desk_pemesanan'  => 'Sewa kebaya wisuda',
            'status_pesan'    => 'pending',
            'total_harga'     => 150000,
        ]);

        $this->actingAs($admin);

        $response = $this->delete(route('produk.destroy', $product->id));
        $response->assertSessionHas('error');

        // Product still exists in DB but is safely marked tidak_tersedia
        $this->assertDatabaseHas('products', [
            'id'            => $product->id,
            'status_produk' => 'tidak_tersedia',
        ]);
    }

    /**
     * BUG-025: Register requires password confirmation.
     */
    public function test_register_requires_password_confirmation(): void
    {
        $response = $this->post(route('register'), [
            'nama_lengkap'          => 'New User',
            'username'              => 'newuser123',
            'email'                 => 'newuser123@example.com',
            'no_telp'               => '081234567890',
            'password'              => 'secret123',
            'password_confirmation' => 'mismatch123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test riwayat-pesanan page loads cleanly without undefined variable $orderGroups error.
     */
    public function test_customer_history_page_loads_without_variable_error(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $response = $this->withoutMiddleware(\App\Http\Middleware\CssHotReloadMiddleware::class)
            ->get('/riwayat-pesanan');
        $response->assertStatus(200);
        $response->assertViewHas('orderGroups');
        $response->assertSee('Riwayat Pesanan Saya');
    }

    /**
     * Test navbar account button points to logout when authenticated and login when guest.
     */
    public function test_navbar_account_button_behavior(): void
    {
        // 1. Guest: should see login route
        $resGuest = $this->get('/');
        $resGuest->assertStatus(200);
        $resGuest->assertSee(route('login'));

        // 2. Authenticated: should see logout route
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $resAuth = $this->get('/');
        $resAuth->assertStatus(200);
        $resAuth->assertSee(route('logout'));
    }

    /**
     * Test admin cart route is removed (404) and admin pages do not contain admin cart menu.
     * Meanwhile, customer cart is fully functional.
     */
    public function test_admin_cart_removed_and_customer_cart_preserved(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        // 1. Admin cart route returns 404
        $resAdminCart = $this->get('/admin/keranjang');
        $resAdminCart->assertStatus(404);

        // 2. Admin pages do not contain /admin/keranjang link in sidebar
        $adminPages = ['/dashboard', '/pesanan', '/produk', '/kategori', '/pengguna'];
        foreach ($adminPages as $page) {
            $res = $this->get($page);
            $res->assertStatus(200);
            $res->assertDontSee('/admin/keranjang');
        }

        // 3. Customer cart route still exists and returns 200
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $resCustomerCart = $this->get('/keranjang');
        $resCustomerCart->assertStatus(200);
        $resCustomerCart->assertSee('Keranjang Sewa');
    }

    /**
     * Test admin logout via POST and GET succeeds cleanly, redirecting to login with 200,
     * invalidating session, and preventing subsequent access to /dashboard.
     */
    public function test_admin_logout_flow_and_session_invalidation(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        // 1. Admin can access dashboard initially
        $this->get('/dashboard')->assertStatus(200);

        // 2. Perform logout via POST
        $resLogout = $this->post(route('logout'));
        $resLogout->assertRedirect(route('login'));
        $resLogout->assertSessionHas('success');
        $this->assertGuest();

        // 3. Follow redirect to login page -> status 200 (not 404!)
        $this->get(route('login'))->assertStatus(200)->assertSee('Sign In');

        // 4. Directly attempting to open dashboard after logout -> denied, redirects to login
        $this->get('/dashboard')->assertRedirect(route('login'));

        // 5. Test logout via GET also succeeds cleanly (no 405 Method Not Allowed, no 404)
        $this->actingAs($admin);
        $resGetLogout = $this->get(route('logout'));
        $resGetLogout->assertRedirect(route('login'));
        $this->assertGuest();

        // 6. Test admin.logout alias route (/admin/logout) via POST and GET
        $this->actingAs($admin);
        $this->post(route('admin.logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        $this->actingAs($admin);
        $this->get(route('admin.logout'))->assertRedirect(route('login'));
        $this->assertGuest();

        // 7. Test /admin and /admin/login shortcuts
        $this->get('/admin')->assertRedirect(route('login'));
        $this->get('/admin/login')->assertRedirect(route('login'));
    }

    /**
     * Test /api/kategori returns proper caching headers for performance optimization.
     */
    public function test_api_kategori_has_cache_headers(): void
    {
        $response = $this->get('/api/kategori');
        $response->assertStatus(200);
        $response->assertHeader('Cache-Control', 'max-age=3600, public');
    }

    /**
     * AUDIT BUG FIX 1: Batas Nomor WhatsApp di codingan disamakan dengan database (maks 20 karakter).
     */
    public function test_whatsapp_max_length_validation_enforced_at_20_chars(): void
    {
        $product = $this->createProduct(['stok' => 5]);

        // WhatsApp > 20 characters must fail validation
        $response = $this->postJson(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Uji',
            'no_whatsapp'   => '0812345678901234567890', // 22 chars
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160/50',
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]]),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['no_whatsapp']);

        // WhatsApp <= 20 characters passes validation
        $responsePass = $this->postJson(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Uji',
            'no_whatsapp'   => '0812345678901234', // 16 chars
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160/50',
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]]),
        ]);

        $responsePass->assertStatus(200);
        $responsePass->assertJson(['success' => true]);
    }

    /**
     * AUDIT BUG FIX 2: Batas Isian TB/BB di codingan disamakan dengan kapasitas database (maks 20 karakter).
     */
    public function test_tb_bb_max_length_validation_enforced_at_20_chars(): void
    {
        $product = $this->createProduct(['stok' => 5]);

        // TB/BB > 20 characters must fail validation
        $response = $this->postJson(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Uji',
            'no_whatsapp'   => '081234567890',
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => 'Tinggi 160cm Berat 50kg Ukuran M', // 32 chars
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]]),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['tb_bb']);

        // TB/BB <= 20 characters passes validation
        $responsePass = $this->postJson(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Uji',
            'no_whatsapp'   => '081234567890',
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160 cm / 50 kg', // 14 chars
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]]),
        ]);

        $responsePass->assertStatus(200);
        $responsePass->assertJson(['success' => true]);
    }

    /**
     * AUDIT BUG FIX 3: Keterangan pesanan dapat menampung teks panjang (>50 karakter) tanpa database error.
     */
    public function test_desk_pemesanan_supports_long_text_without_database_overflow(): void
    {
        // Long product name: 60 chars
        $longProductName = 'Kebaya Modern Brokat Mewah Payet Mutiara Gradasi Sage Terracotta';
        $product = $this->createProduct([
            'nama_produk' => $longProductName,
            'stok'        => 5,
        ]);

        $response = $this->postJson(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Kebaya Mewah',
            'no_whatsapp'   => '081234567890',
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '165/52',
            'tanggal_acara' => '2026-12-31',
            'items'         => json_encode([['id' => $product->id, 'nama' => $longProductName, 'jumlah' => 1]]),
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verify order saved in DB with desk_pemesanan > 50 chars successfully
        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertGreaterThan(50, strlen($order->desk_pemesanan));
        $this->assertStringContainsString($longProductName, $order->desk_pemesanan);
    }

    /**
     * AUDIT BUG FIX 4 & 5: Kolom kode_pesanan terdaftar di DB orders dan foto_kebaya di products telah dibersihkan.
     */
    public function test_schema_kode_pesanan_exists_and_foto_kebaya_dropped(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Schema::hasColumn('orders', 'kode_pesanan'));
        $this->assertFalse(\Illuminate\Support\Facades\Schema::hasColumn('products', 'foto_kebaya'));
    }
}
