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

class CabilRentDatabaseFixesTest extends TestCase
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
            'nama_lengkap' => 'Customer Test',
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

    /**
     * 1. Pengujian batas nomor WhatsApp > 20 karakter ditolak validasi.
     */
    public function test_whatsapp_number_exceeding_20_chars_fails_validation()
    {
        $product = $this->createProduct();

        $response = $this->post(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Uji WA',
            'no_whatsapp'   => '081234567890123456789', // 21 karakter
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160 cm / 50 kg',
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([
                ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
            ])
        ]);

        $response->assertSessionHasErrors('no_whatsapp');
    }

    /**
     * 1b. Pengujian nomor WhatsApp <= 20 karakter diterima validasi.
     */
    public function test_whatsapp_number_within_20_chars_passes_validation()
    {
        $product = $this->createProduct();

        $response = $this->post(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Uji WA Valid',
            'no_whatsapp'   => '081234567890', // 12 karakter
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160 cm / 50 kg',
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([
                ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
            ])
        ]);

        $response->assertSessionHasNoErrors();
    }

    /**
     * 2. Pengujian batas isian TB/BB > 30 karakter ditolak validasi.
     */
    public function test_tb_bb_exceeding_30_chars_fails_validation()
    {
        $product = $this->createProduct();

        $response = $this->post(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Uji TB/BB',
            'no_whatsapp'   => '081234567890',
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '165 cm / 55 kg proporsional sekali tinggi', // 42 karakter
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([
                ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
            ])
        ]);

        $response->assertSessionHasErrors('tb_bb');
    }

    /**
     * 2b. Pengujian batas isian TB/BB hingga 30 karakter berhasil disimpan di database.
     */
    public function test_tb_bb_up_to_30_chars_saves_successfully()
    {
        $product = $this->createProduct();
        $tbBbTest = '165 cm / 55 kg (proporsional)'; // 29 karakter
        $this->assertLessThanOrEqual(30, strlen($tbBbTest));

        $response = $this->post(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan TB/BB 30',
            'no_whatsapp'   => '081234567891',
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => $tbBbTest,
            'tanggal_acara' => now()->addDays(5)->toDateString(),
            'items'         => json_encode([
                ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
            ])
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'no_whatsapp' => '081234567891',
            'tb_bb'       => $tbBbTest,
        ]);
    }

    /**
     * 3. Pengujian nama produk panjang disimpan utuh di desk_pemesanan tanpa truncation error.
     */
    public function test_long_product_name_saved_in_desk_pemesanan_without_truncation()
    {
        $longName = 'Kebaya Brokat Sutra Encim Modern Glamour Gold Pesta Resepsi Pernikahan Kalimantan';
        $product = $this->createProduct(['nama_produk' => $longName]);

        $response = $this->post(route('order.checkout'), [
            'nama_lengkap'  => 'Pelanggan Nama Panjang',
            'no_whatsapp'   => '081234567892',
            'domisili'      => 'Banjarmasin',
            'tb_bb'         => '160 cm / 50 kg',
            'tanggal_acara' => '2026-12-15',
            'items'         => json_encode([
                ['id' => $product->id, 'nama' => $product->nama_produk, 'jumlah' => 1]
            ])
        ]);

        $response->assertSessionHasNoErrors();

        $savedOrder = Order::where('no_whatsapp', '081234567892')->latest()->first();
        $this->assertNotNull($savedOrder);
        $this->assertStringContainsString($longName, $savedOrder->desk_pemesanan);
        $this->assertStringContainsString('(Acara: 2026-12-15)', $savedOrder->desk_pemesanan);
        $this->assertLessThanOrEqual(150, strlen($savedOrder->desk_pemesanan));
    }

    /**
     * 4 & 5. Pengujian checkout beberapa produk sekaligus berbagi satu kode_pesanan yang sama.
     */
    public function test_multi_item_checkout_shares_single_kode_pesanan()
    {
        $p1 = $this->createProduct(['nama_produk' => 'Kebaya Modern Peach']);
        $p2 = $this->createProduct(['nama_produk' => 'Heels Pesta Emas']);

        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $items = [
            ['id' => $p1->id, 'nama' => $p1->nama_produk, 'jumlah' => 1],
            ['id' => $p2->id, 'nama' => $p2->nama_produk, 'jumlah' => 1],
        ];

        $response = $this->postJson(route('order.checkout'), [
            'nama_lengkap'  => $customer->nama_lengkap,
            'no_whatsapp'   => '081234567893',
            'domisili'      => 'Banjarbaru',
            'tb_bb'         => '168 cm / 58 kg',
            'tanggal_acara' => '2026-12-20',
            'items'         => $items,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertNotEmpty($data['kode_pesanan']);
        $this->assertCount(2, $data['order_ids']);

        $createdOrders = Order::whereIn('id', $data['order_ids'])->get();
        $this->assertCount(2, $createdOrders);

        // Verifikasi bahwa seluruh order dalam checkout ini memiliki KODE PESANAN YANG PERSIS SAMA
        $this->assertEquals($data['kode_pesanan'], $createdOrders[0]->kode_pesanan);
        $this->assertEquals($data['kode_pesanan'], $createdOrders[1]->kode_pesanan);
        $this->assertEquals($createdOrders[0]->kode_pesanan, $createdOrders[1]->kode_pesanan);

        // Verifikasi relasi Basket::orders dan Basket::order
        $basket1 = Basket::find($createdOrders[0]->basket_id);
        $this->assertNotNull($basket1);
        $this->assertTrue($basket1->orders()->exists());
        $this->assertNotNull($basket1->order);
        $this->assertEquals($createdOrders[0]->id, $basket1->order->id);
    }

    /**
     * 6. Pengujian keselarasan Excel: Tabel_Database_CabilRent_FIXXXXXXXBANGETTTT.xlsx memuat kode_pesanan, tb_bb=30, desk_pemesanan=150.
     */
    public function test_excel_database_consistency()
    {
        $excelPath = 'C:/Users/acer/Downloads/Tabel_Database_CabilRent_FIXXXXXXXBANGETTTT.xlsx';
        $this->assertFileExists($excelPath);

        $script = 'C:/Users/acer/.gemini/antigravity-cli/brain/a9408e52-8fcb-42a8-96a6-fae0f43cf82a/scratch/test_excel_content.py';
        file_put_contents($script, 'import openpyxl
wb = openpyxl.load_workbook(r"C:/Users/acer/Downloads/Tabel_Database_CabilRent_FIXXXXXXXBANGETTTT.xlsx", data_only=True)
ws = wb["Sheet1"]
cols = []
for r in range(12, 24):
    cols.append(f"{ws.cell(r, 6).value}|{ws.cell(r, 7).value}")
print(",".join(cols))
');

        $output = shell_exec("python {$script}");
        $this->assertNotNull($output);
        $this->assertStringContainsString('kode_pesanan', $output);
        $this->assertStringContainsString('150', $output);
        $this->assertStringContainsString('30', $output);
    }
}
