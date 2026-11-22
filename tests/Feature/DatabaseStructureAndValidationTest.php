<?php

namespace Tests\Feature;

use App\Models\Basket;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseStructureAndValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_basket_jumlah_cannot_be_zero_or_negative(): void
    {
        $user = User::create([
            'nama_lengkap' => 'Tester User',
            'username'     => 'tester1',
            'email'        => 'tester1@cabilrent.id',
            'no_telp'      => '081234567899',
            'password'     => bcrypt('secret'),
            'role'         => 'customers',
        ]);
        $this->actingAs($user);

        $cat = Category::create([
            'nama_kategori' => 'Kebaya',
            'slug'          => 'kebaya-test',
        ]);

        $product = Product::create([
            'kategori_id'   => $cat->id,
            'nama_produk'   => 'Kebaya Test',
            'slug'          => 'kebaya-test-slug',
            'harga_sewa'    => 100000,
            'foto_produk'   => 'test.png',
            'status_produk' => 'tersedia',
            'stok'          => 5,
        ]);

        // 1. Validation error on addItem when jumlah is 0
        $response0 = $this->postJson(route('cart.add'), [
            'produk_id' => $product->id,
            'jumlah'    => 0,
        ]);
        $response0->assertStatus(422);
        $response0->assertJsonValidationErrors(['jumlah']);
        $this->assertEquals(
            'Jumlah keranjang minimal 1 dan tidak boleh 0 atau negatif.',
            $response0->json('errors.jumlah.0')
        );

        // 2. Validation error on addItem when jumlah is negative
        $responseNeg = $this->postJson(route('cart.add'), [
            'produk_id' => $product->id,
            'jumlah'    => -5,
        ]);
        $responseNeg->assertStatus(422);
        $responseNeg->assertJsonValidationErrors(['jumlah']);
        $this->assertEquals(
            'Jumlah keranjang minimal 1 dan tidak boleh 0 atau negatif.',
            $responseNeg->json('errors.jumlah.0')
        );

        // 3. Successful addItem with valid jumlah (>= 1)
        $responseValid = $this->postJson(route('cart.add'), [
            'produk_id' => $product->id,
            'jumlah'    => 2,
        ]);
        $responseValid->assertStatus(200);
        $this->assertDatabaseHas('baskets', [
            'user_id'   => $user->id,
            'produk_id' => $product->id,
            'jumlah'    => 2,
        ]);

        // 4. UpdateItem validation error when jumlah is 0
        $basket = Basket::where('user_id', $user->id)->first();
        $responseUpdate0 = $this->patchJson(route('cart.update', $basket->id), [
            'jumlah' => 0,
        ]);
        $responseUpdate0->assertStatus(422);
        $responseUpdate0->assertJsonValidationErrors(['jumlah']);

        // 5. UpdateItem valid
        $responseUpdateValid = $this->patchJson(route('cart.update', $basket->id), [
            'jumlah' => 3,
        ]);
        $responseUpdateValid->assertStatus(200);
        $this->assertDatabaseHas('baskets', [
            'id'     => $basket->id,
            'jumlah' => 3,
        ]);

        // 6. Model level exception when saving <= 0
        $this->expectException(\InvalidArgumentException::class);
        $basket->jumlah = 0;
        $basket->save();
    }
}
