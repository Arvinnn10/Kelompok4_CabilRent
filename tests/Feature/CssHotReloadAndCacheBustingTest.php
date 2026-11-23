<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CssHotReloadAndCacheBustingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Helper asset_versioned() menghasilkan URL dengan query param ?v=filemtime riil.
     */
    public function test_asset_versioned_appends_filemtime_query_parameter(): void
    {
        $cssPath = 'css/home.css';
        $fullPath = public_path($cssPath);

        $this->assertFileExists($fullPath);
        $expectedMtime = filemtime($fullPath);

        $versionedUrl = asset_versioned($cssPath);

        $this->assertStringContainsString('css/home.css?v=' . $expectedMtime, $versionedUrl);
    }

    /**
     * Test 2: Endpoint development /_css-dev/status mengembalikan status 200 dan daftar timestamp file CSS.
     */
    public function test_css_dev_status_endpoint_returns_json_in_local_environment(): void
    {
        $response = $this->get('/_css-dev/status');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'timestamp',
            'files' => [
                'home.css',
                'dashboard.css',
                'keranjang.css',
            ],
        ]);

        $cacheControl = $response->headers->get('Cache-Control');
        $this->assertStringContainsString('no-cache', $cacheControl);
        $this->assertStringContainsString('no-store', $cacheControl);
    }

    /**
     * Test 3: Middleware CssHotReload menginjeksi client script pada respon HTML di environment local.
     */
    public function test_css_hot_reload_middleware_injects_script_in_local_html_response(): void
    {
        $response = $this->get('/kontak');

        $response->assertStatus(200);
        $response->assertSee('<!-- [CabilRent Dev] Automatic CSS Hot Reload -->', false);
        $response->assertSee('js/css-hot-reload.js', false);
        $response->assertSee('data-dev-endpoint=', false);
    }

    /**
     * Test 4: Halaman Customer memuat stylesheet dengan versioning query param ?v=.
     */
    public function test_customer_pages_render_with_versioned_stylesheets(): void
    {
        $pages = ['/', '/koleksi', '/tentang', '/kontak', '/login', '/register'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee('.css?v=', false);
        }
    }

    /**
     * Test 5: Halaman Admin memuat stylesheet dengan versioning query param ?v=.
     */
    public function test_admin_pages_render_with_versioned_stylesheets(): void
    {
        $admin = User::create([
            'nama_lengkap' => 'Admin Test',
            'username'     => 'admin_css_test',
            'email'        => 'admin_css@cabilrent.id',
            'no_telp'      => '081234999888',
            'password'     => bcrypt('secret123'),
            'role'         => 'admin',
            'status'       => 'aktif',
        ]);

        $this->actingAs($admin);

        $adminPages = ['/dashboard', '/pesanan', '/produk', '/kategori', '/pengguna'];

        foreach ($adminPages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee('css/dashboard.css?v=', false);
        }
    }

    /**
     * Test 6: Pada environment production, endpoint dev 404 & hot reload script TIDAK diinjeksi, namun cache busting tetap aktif.
     */
    public function test_production_environment_disables_hot_reload_and_preserves_cache_busting(): void
    {
        // Set environment ke production
        app()->detectEnvironment(fn() => 'production');

        // 1. Endpoint /_css-dev/status tidak boleh dapat diakses di production
        $devResponse = $this->get('/_css-dev/status');
        $this->assertTrue(in_array($devResponse->status(), [404, 403, 500]));

        // 2. Respon HTML halaman tidak boleh menginjeksi script dev hot reload
        $htmlResponse = $this->get('/kontak');
        $htmlResponse->assertStatus(200);
        $htmlResponse->assertDontSee('<!-- [CabilRent Dev] Automatic CSS Hot Reload -->', false);
        $htmlResponse->assertDontSee('js/css-hot-reload.js', false);

        // 3. Cache busting asset_versioned() TETAP aktif di production
        $versionedUrl = asset_versioned('css/home.css');
        $this->assertStringContainsString('css/home.css?v=', $versionedUrl);
    }

    /**
     * Test 7: Halaman Customer memuat file JavaScript dengan versioning query param ?v=.
     */
    public function test_customer_pages_render_with_versioned_javascript(): void
    {
        $pages = ['/', '/koleksi', '/tentang', '/kontak', '/keranjang', '/pemesanan'];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee('.js?v=', false);
        }
    }

    /**
     * Test 8: Halaman Koleksi dan Detail mengembalikan header Cache-Control must-revalidate.
     */
    public function test_koleksi_and_detail_return_revalidation_cache_headers(): void
    {
        $resKoleksi = $this->get('/koleksi');
        $resKoleksi->assertStatus(200);
        $headerKoleksi = $resKoleksi->headers->get('Cache-Control');
        $this->assertStringContainsString('must-revalidate', $headerKoleksi);

        // Buat kategori dan produk uji untuk detail
        $cat = \App\Models\Category::create(['nama_kategori' => 'Kebaya Wisuda', 'slug' => 'kebaya-wisuda']);
        $product = \App\Models\Product::create([
            'kategori_id'   => $cat->id,
            'nama_produk'   => 'Kebaya Kartini Test',
            'slug'          => 'kebaya-kartini-test',
            'harga_sewa'    => 150000,
            'foto_produk'   => 'koleksi/kebaya-coco.png',
            'status_produk' => 'tersedia',
            'stok'          => 3,
        ]);

        $resDetailSlug = $this->get('/detail?id=' . $product->slug);
        $resDetailSlug->assertStatus(200);
        $headerDetailSlug = $resDetailSlug->headers->get('Cache-Control');
        $this->assertStringContainsString('must-revalidate', $headerDetailSlug);
        $resDetailSlug->assertSee('js/detail.js?v=', false);

        $resDetailId = $this->get('/detail?id=' . $product->id);
        $resDetailId->assertStatus(200);
        $resDetailId->assertSee($product->nama_produk);
    }
}
