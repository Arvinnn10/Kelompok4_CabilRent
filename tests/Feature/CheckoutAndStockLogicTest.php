<?php

namespace Tests\Feature;

use App\Http\Middleware\CssHotReloadMiddleware;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutAndStockLogicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(CssHotReloadMiddleware::class);
    }

    private function createCustomer(array $overrides = []): User
    {
        return User::create(array_merge([
            'nama_lengkap' => 'Pelanggan Uji',
            'username'     => 'cust_' . uniqid(),
            'email'        => 'cust_' . uniqid() . '@example.com',
            'no_telp'      => '08' . rand(1000000000, 9999999999),
            'password'     => bcrypt('password123'),
            'role'         => 'customers',
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
            'stok'          => 5,
        ], $overrides));
    }

    private function basePayload(User $customer, array $items, array $overrides = []): array
    {
        return array_merge([
            'nama_lengkap'  => $customer->nama_lengkap,
            'no_whatsapp'   => $customer->no_telp,
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160 cm / 50 kg',
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => $items,
        ], $overrides);
    }

    /**
     * Skenario 1: Checkout satu produk dengan stok tersedia.
     */
    public function test_01_checkout_single_product_success(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 3]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertNotEmpty($data['kode_pesanan']);
        $this->assertCount(1, $data['order_ids']);

        $order = Order::find($data['order_ids'][0]);
        $this->assertNotNull($order);
        $this->assertEquals(1, $order->basket->jumlah);
    }

    /**
     * Skenario 2: Checkout beberapa produk berbeda dalam satu invoice (kode_pesanan sama).
     */
    public function test_02_checkout_multiple_different_products_shared_invoice(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $p1 = $this->createProduct(['nama_produk' => 'Kebaya Modern', 'stok' => 5]);
        $p2 = $this->createProduct(['nama_produk' => 'Heels Gold', 'stok' => 4]);

        $payload = $this->basePayload($customer, [
            ['id' => $p1->id, 'nama' => $p1->nama_produk, 'jumlah' => 1],
            ['id' => $p2->id, 'nama' => $p2->nama_produk, 'jumlah' => 2],
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertCount(2, $data['order_ids']);

        $orders = Order::whereIn('id', $data['order_ids'])->get();
        $this->assertCount(2, $orders);
        $this->assertEquals($orders[0]->kode_pesanan, $orders[1]->kode_pesanan);
    }

    /**
     * Skenario 3: Checkout produk yang sama dengan ID berulang menghasilkan tepat 1 baris pesanan.
     */
    public function test_03_checkout_same_product_duplicate_ids_creates_single_order_row(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 10]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1],
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 2],
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertCount(1, $data['order_ids'], 'Harus menghasilkan tepat 1 baris order per produk');

        $order = Order::find($data['order_ids'][0]);
        $this->assertEquals(3, $order->basket->jumlah);
    }

    /**
     * Skenario 4: Penggabungan jumlah produk duplikat (Kebaya A x2 + Kebaya A x3 = Kebaya A x5).
     */
    public function test_04_merge_duplicate_product_quantities_sums_correctly(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['nama_produk' => 'Kebaya Sutra', 'harga_sewa' => 200000, 'stok' => 10]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 2],
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 3],
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);

        $order = Order::find($data['order_ids'][0]);
        $this->assertEquals(5, $order->basket->jumlah, 'Jumlah total harus 5 (2 + 3)');

        // Perhitungan subtotal (Rp 200.000 * 5) + deposit (Rp 100.000 * 5) = Rp 1.500.000
        $this->assertEquals(1500000, $order->total_harga);
    }

    /**
     * Skenario 5: Jumlah pesanan melebihi stok memicu pop-up konfirmasi penyesuaian stok.
     */
    public function test_05_requested_qty_exceeding_stock_triggers_confirmation(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 3]);

        // Pesan 5 unit, tetapi stok hanya 3
        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 5]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertTrue($data['needs_confirmation']);
        $this->assertEquals(5, $data['adjustments'][0]['original_qty']);
        $this->assertEquals(3, $data['adjustments'][0]['adjusted_qty']);

        // Transaksi belum disimpan
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 6: Produk dengan stok 0 otomatis dilewati dan butuh konfirmasi untuk item yang tersisa.
     */
    public function test_06_product_with_zero_stock_is_skipped(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $pAvailable = $this->createProduct(['nama_produk' => 'Kebaya Tersedia', 'stok' => 2]);
        $pZeroStock = $this->createProduct(['nama_produk' => 'Kebaya Habis', 'stok' => 0]);

        $payload = $this->basePayload($customer, [
            ['id' => $pAvailable->id, 'nama' => $pAvailable->nama_produk, 'jumlah' => 1],
            ['id' => $pZeroStock->id, 'nama' => $pZeroStock->nama_produk, 'jumlah' => 1],
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertTrue($data['needs_confirmation']);
        $this->assertEquals('unavailable', $data['adjustments'][0]['status']);
        $this->assertEquals(0, $data['adjustments'][0]['adjusted_qty']);

        // Transaksi belum disimpan sebelum konfirmasi
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 7: Produk dengan status tidak tersedia otomatis dilewati.
     */
    public function test_07_product_with_status_tidak_tersedia_is_skipped(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $pAvailable = $this->createProduct(['nama_produk' => 'Kebaya Siap', 'stok' => 3, 'status_produk' => 'tersedia']);
        $pUnavailable = $this->createProduct(['nama_produk' => 'Kebaya Rusak', 'stok' => 3, 'status_produk' => 'tidak_tersedia']);

        $payload = $this->basePayload($customer, [
            ['id' => $pAvailable->id, 'nama' => $pAvailable->nama_produk, 'jumlah' => 1],
            ['id' => $pUnavailable->id, 'nama' => $pUnavailable->nama_produk, 'jumlah' => 1],
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertTrue($data['needs_confirmation']);
        $this->assertEquals('unavailable', $data['adjustments'][0]['status']);
    }

    /**
     * Skenario 8: Semua produk tidak tersedia membatalkan checkout tanpa membuat transaksi kosong.
     */
    public function test_08_all_products_unavailable_aborts_checkout_without_empty_transaction(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $p1 = $this->createProduct(['stok' => 0]);
        $p2 = $this->createProduct(['stok' => 2, 'status_produk' => 'tidak_tersedia']);

        $payload = $this->basePayload($customer, [
            ['id' => $p1->id, 'nama' => $p1->nama_produk, 'jumlah' => 1],
            ['id' => $p2->id, 'nama' => $p2->nama_produk, 'jumlah' => 1],
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertTrue($data['all_unavailable']);
        $this->assertStringContainsString('seluruh produk yang Anda pilih saat ini sedang tidak tersedia', $data['message']);

        // Pastikan tidak ada transaksi/order kosong yang dibuat
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 9: Stok berubah saat checkout memicu penyesuaian ulang dan pop-up konfirmasi baru.
     */
    public function test_09_stock_changed_before_save_triggers_new_confirmation(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 4]);

        // Pengguna konfirmasi untuk 4 unit, tetapi stok tiba-tiba turun menjadi 2
        $product->update(['stok' => 2]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 4]
        ], ['confirmed_adjustment' => false]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertFalse($data['success']);
        $this->assertTrue($data['needs_confirmation']);
        $this->assertEquals(2, $data['adjustments'][0]['adjusted_qty']);

        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 10: Customer menyetujui perubahan jumlah melalui pop-up (confirmed_adjustment = true).
     */
    public function test_10_customer_approves_adjustment_proceeds_with_adjusted_stock(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 3]);

        // Customer mengirimkan konfirmasi penyesuaian menjadi 3 unit
        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 3]
        ], ['confirmed_adjustment' => true]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertCount(1, $data['order_ids']);

        $order = Order::find($data['order_ids'][0]);
        $this->assertEquals(3, $order->basket->jumlah);
    }

    /**
     * Skenario 11: Customer menolak perubahan jumlah melalui pop-up (tidak mengirim konfirmasi).
     */
    public function test_11_customer_rejects_adjustment_cancels_checkout_no_orders_created(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 2]);

        // Customer meminta 5, mendapat respon needs_confirmation, namun membatalkan dialog
        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 5]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);
        $this->assertTrue($response->json('needs_confirmation'));

        // Tidak ada panggilan lanjutan: pastikan 0 order di database
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 12: Pengiriman request checkout berulang dicegah oleh validasi stok dan transaksi locking.
     */
    public function test_12_repeated_checkout_requests_prevent_overselling(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 1]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
        ]);

        // Request pertama berhasil
        $res1 = $this->postJson(route('order.checkout'), $payload);
        $res1->assertStatus(200);
        $this->assertTrue($res1->json('success'));

        // Simulasi stok telah habis terpotong setelah pemrosesan pesanan pertama
        $product->update(['stok' => 0, 'status_produk' => 'tidak_tersedia']);

        // Request kedua sekarang mendeteksi stok 0 -> otomatis dibatalkan/all_unavailable
        $res2 = $this->postJson(route('order.checkout'), $payload);
        $res2->assertStatus(200);
        $this->assertTrue($res2->json('all_unavailable'));
    }

    /**
     * Skenario 13: Kode pesanan tetap sama untuk seluruh produk dalam satu checkout.
     */
    public function test_13_kode_pesanan_is_identical_for_all_items_in_single_checkout(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $p1 = $this->createProduct(['nama_produk' => 'Busana 1', 'stok' => 5]);
        $p2 = $this->createProduct(['nama_produk' => 'Busana 2', 'stok' => 5]);
        $p3 = $this->createProduct(['nama_produk' => 'Busana 3', 'stok' => 5]);

        $payload = $this->basePayload($customer, [
            ['id' => $p1->id, 'nama' => $p1->nama_produk, 'jumlah' => 1],
            ['id' => $p2->id, 'nama' => $p2->nama_produk, 'jumlah' => 1],
            ['id' => $p3->id, 'nama' => $p3->nama_produk, 'jumlah' => 1],
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);
        $response->assertStatus(200);
        $orderIds = $response->json('order_ids');

        $uniqueCodes = Order::whereIn('id', $orderIds)->pluck('kode_pesanan')->unique();
        $this->assertCount(1, $uniqueCodes, 'Seluruh produk harus memiliki kode_pesanan yang persis sama');
    }

    /**
     * Skenario 14: Data enam keranjang duplikat historis tidak berubah.
     */
    public function test_14_historical_duplicate_baskets_remain_untouched(): void
    {
        $user3 = new User([
            'nama_lengkap' => 'User Tiga',
            'username'     => 'user3',
            'email'        => 'user3@example.com',
            'no_telp'      => '081234567833',
            'password'     => bcrypt('secret'),
            'role'         => 'customers',
            'status'       => 'aktif',
        ]);
        $user3->id = 3;
        $user3->save();

        $cat = Category::firstOrCreate(['nama_kategori' => 'Kebaya'], ['slug' => 'kebaya']);
        $product2 = new Product([
            'kategori_id'   => $cat->id,
            'nama_produk'   => 'Ivory Palazzo Kebaya',
            'slug'          => 'kebaya-ivory-palazzo',
            'harga_sewa'    => 200000,
            'foto_produk'   => 'kebaya-ivory.jpg',
            'status_produk' => 'tersedia',
            'stok'          => 5,
        ]);
        $product2->id = 2;
        $product2->save();

        // Buat 6 basket historis (ID 4-9) untuk user 3 & produk 2
        for ($i = 4; $i <= 9; $i++) {
            $hb = new Basket([
                'user_id'       => 3,
                'produk_id'     => 2,
                'jumlah'        => 1,
                'tanggal_acara' => '2026-11-21',
            ]);
            $hb->id = $i;
            $hb->save();
        }

        $this->actingAs($user3);

        // User 3 melakukan checkout untuk produk 2 sebanyak 1 unit
        $payload = $this->basePayload($user3, [
            ['id' => 2, 'nama' => $product2->nama_produk, 'jumlah' => 1]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);
        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));

        // Verifikasi bahwa keenam basket historis ID 4-9 tetap utuh, tidak diupdate, dan tidak memiliki order
        $historicalBaskets = Basket::whereIn('id', [4, 5, 6, 7, 8, 9])->get();
        $this->assertCount(6, $historicalBaskets);
        foreach ($historicalBaskets as $hb) {
            $this->assertEquals(1, $hb->jumlah);
            $this->assertEquals('2026-11-21', $hb->tanggal_acara ? $hb->tanggal_acara->toDateString() : null);
            $this->assertFalse($hb->orders()->exists(), "Basket {$hb->id} tidak boleh dikaitkan ke order baru");
        }
    }

    /**
     * Skenario 15: Validasi kuantitas 0 ditolak dengan status HTTP 422.
     */
    public function test_15_quantity_zero_is_rejected_with_422(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 5]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 0]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('tidak valid', $response->json('message'));
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 16: Validasi kuantitas negatif ditolak dengan status HTTP 422.
     */
    public function test_16_quantity_negative_is_rejected_with_422(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 5]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => -3]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('tidak valid', $response->json('message'));
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 17: Validasi kuantitas string kosong ditolak dengan status HTTP 422.
     */
    public function test_17_quantity_empty_string_is_rejected_with_422(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 5]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => '']
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('tidak valid', $response->json('message'));
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 18: Validasi kuantitas teks non-numerik ditolak dengan status HTTP 422.
     */
    public function test_18_quantity_text_string_is_rejected_with_422(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 5]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 'tiga_buah']
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('tidak valid', $response->json('message'));
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 19: Validasi kuantitas angka pecahan / desimal ditolak dengan status HTTP 422.
     */
    public function test_19_quantity_float_is_rejected_with_422(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 5]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 2.5]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('tidak valid', $response->json('message'));
        $this->assertEquals(0, Order::count());
    }

    /**
     * Skenario 20: Validasi kuantitas bilangan bulat positif valid diterima dengan status HTTP 200.
     */
    public function test_20_quantity_valid_positive_is_accepted_with_200(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 5]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 2]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(1, Order::count());
        $this->assertEquals(2, Order::first()->basket->jumlah);
    }

    /**
     * Skenario 21: Item keranjang baru tanpa properti jumlah/qty otomatis default 1 dan berhasil HTTP 200.
     */
    public function test_21_checkout_item_without_quantity_defaults_to_one_success(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 5]);

        // Payload hanya memiliki id dan nama tanpa jumlah atau qty
        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, Order::first()->basket->jumlah);
    }

    /**
     * Skenario 22: Checkout item dengan properti qty (bukan jumlah) berhasil diterima dengan HTTP 200.
     */
    public function test_22_checkout_item_with_qty_key_success(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct(['stok' => 5]);

        $payload = $this->basePayload($customer, [
            ['id' => $product->id, 'nama' => $product->nama_produk, 'qty' => 3]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(1, Order::count());
        $this->assertEquals(3, Order::first()->basket->jumlah);
    }

    /**
     * Skenario 23: Checkout Arella Kebaya secara spesifik tanpa kuantitas tidak lagi error 422, default 1 sukses.
     */
    public function test_23_checkout_arella_kebaya_without_quantity_succeeds(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);
        $product = $this->createProduct([
            'nama_produk' => 'Arella Kebaya',
            'slug'        => 'kebaya-arella',
            'stok'        => 4,
        ]);

        $payload = $this->basePayload($customer, [
            [
                'id'         => $product->id,
                'nama'       => 'Arella Kebaya',
                'kategori'   => 'Kebaya Wisuda',
                'keterangan' => 'Arella Kebaya wisuda elegan',
                'harga'      => 250000,
                'gambar'     => '/images/kebaya-arella.png',
                'durasi'     => 3,
            ]
        ]);

        $response = $this->postJson(route('order.checkout'), $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(1, Order::count());
        $this->assertEquals(1, Order::first()->basket->jumlah);
        $this->assertEquals('Arella Kebaya', Order::first()->basket->product->nama_produk);
    }
}
