<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\Basket;
use App\Models\Product;
use App\Models\User;

$order = Order::find(6);
if ($order) {
    echo "Order 6 found: kode=" . $order->kode_pesanan . ", basket_id=" . $order->basket_id . PHP_EOL;
    $basketId = $order->basket_id;
    $order->delete();
    if ($basketId) {
        $b = Basket::find($basketId);
        if ($b) $b->delete();
    }
    echo "Test order 6 cleaned up." . PHP_EOL;
}
$testUser = User::where('no_telp', '081234567899')->first();
if ($testUser) {
    $testUser->delete();
    echo "Test user cleaned up." . PHP_EOL;
}
$arella = Product::find(8);
echo "Arella Kebaya stock: " . $arella->stok . ", status: " . $arella->status_produk . PHP_EOL;
echo "Total orders: " . Order::count() . ", baskets: " . Basket::count() . ", users: " . User::count() . PHP_EOL;
