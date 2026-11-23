<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Basket;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

echo "=== DATABASE INTEGRITY CHECK ===" . PHP_EOL;
echo "Products count: " . Product::count() . PHP_EOL;
echo "Users count: " . User::count() . PHP_EOL;
echo "Baskets count: " . Basket::count() . PHP_EOL;
echo "Orders count: " . Order::count() . PHP_EOL;

$histBaskets = Basket::whereBetween('id', [4, 9])->get();
echo "Historical baskets 4-9 count: " . $histBaskets->count() . PHP_EOL;
foreach ($histBaskets as $b) {
    echo "  Basket ID={$b->id}, user_id={$b->user_id}, produk_id={$b->produk_id}, jumlah={$b->jumlah}" . PHP_EOL;
}

$arella = Product::where('nama_produk', 'like', '%Arella%')->orWhere('slug', 'like', '%arella%')->first();
if ($arella) {
    echo "Arella Kebaya found: ID={$arella->id}, nama={$arella->nama_produk}, stok={$arella->stok}, status={$arella->status_produk}" . PHP_EOL;
} else {
    echo "Arella Kebaya NOT FOUND!" . PHP_EOL;
}

echo PHP_EOL . "=== SIMULATING CHECKOUT FOR ARELLA KEBAYA (WITHOUT QUANTITY) ===" . PHP_EOL;

$orderController = app(\App\Http\Controllers\OrderController::class);

$payload = [
    'nama_lengkap'  => 'Pelanggan Uji Arella',
    'no_whatsapp'   => '081234567899',
    'domisili'      => 'Banjarmasin',
    'tb_bb'         => '160 cm / 50 kg',
    'tanggal_acara' => date('Y-m-d', strtotime('+3 days')),
    'items'         => json_encode([
        [
            'id'         => $arella->id,
            'nama'       => $arella->nama_produk,
            'kategori'   => 'Kebaya Wisuda',
            'keterangan' => 'Arella Kebaya wisuda elegan',
            'harga'      => (int)$arella->harga_sewa,
            'gambar'     => '/images/kebaya-arella.png',
            'durasi'     => 3
            // Catatan: sengaja tanpa 'jumlah' dan tanpa 'qty' untuk menguji kasus user yang error sebelumnya
        ]
    ]),
];

$request = Request::create('/pesanan/checkout', 'POST', $payload);
$request->headers->set('Accept', 'application/json');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');

$initialOrderCount = Order::count();
$response = $orderController->checkout($request);

echo "Response status code: " . $response->getStatusCode() . PHP_EOL;
$content = json_decode($response->getContent(), true);
echo "Response body: " . json_encode($content, JSON_PRETTY_PRINT) . PHP_EOL;

$newOrderCount = Order::count();
echo "Order count before: {$initialOrderCount}, after: {$newOrderCount}" . PHP_EOL;

if ($response->getStatusCode() === 200 && ($content['success'] ?? false) === true) {
    echo "SUCCESS: Checkout for Arella Kebaya without quantity succeeded perfectly with default qty = 1!" . PHP_EOL;
} else {
    echo "FAILED: Checkout did not succeed as expected." . PHP_EOL;
}
