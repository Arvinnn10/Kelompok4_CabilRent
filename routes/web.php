<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Basket;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

if (!function_exists('generateUniqueSlug')) {
    function generateUniqueSlug($modelClass, $title, $ignoreId = null, $column = 'slug') {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug ?: 'item';
        $count = 1;
        while ($modelClass::where($column, $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }
        return $slug;
    }
}

// Halaman utama (Home / Customer Landing)
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return redirect()->route('home');
});

// ROUTES AUTH
Route::middleware(['auth'])->group(function () {

    // ROUTES KHUSUS ADMIN
    Route::middleware(['admin'])->group(function () {

        // DASHBOARD (BUG-001, BUG-024)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        // KATEGORI (BUG-024)
        Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [AdminCategoryController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{id}', [AdminCategoryController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [AdminCategoryController::class, 'destroy'])->name('kategori.destroy');

        // PENGGUNA ADMIN (BUG-012, BUG-024)
        Route::get('/pengguna', [AdminUserController::class, 'index'])->name('pengguna.index');
        Route::patch('/pengguna/{id}/blokir', [AdminUserController::class, 'toggleBlock'])->name('pengguna.blokir');


        // PRODUK ADMIN (BUG-013, BUG-014, BUG-024)
        Route::get('/produk', [AdminProductController::class, 'index'])->name('produk.index');
        Route::post('/produk', [AdminProductController::class, 'store'])->name('produk.store');
        Route::put('/produk/{id}', [AdminProductController::class, 'update'])->name('produk.update');
        Route::patch('/produk/{id}/toggle', [AdminProductController::class, 'toggleStatus'])->name('produk.toggle');
        Route::delete('/produk/{id}', [AdminProductController::class, 'destroy'])->name('produk.destroy');


        // PESANAN ADMIN
        Route::get('/pesanan', function (Request $request) {
            $query = Order::with(['basket.product', 'product', 'user']);

            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status_pesan', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                        ->orWhere('no_whatsapp', 'like', "%{$search}%")
                        ->orWhere('domisili', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('nama_lengkap', 'like', "%{$search}%");
                        });
                });
            }

            $orders = $query->latest()->paginate(10)->withQueryString();
            $totalOrders = Order::count();
            $perluDikonfirmasi = Order::where('status_pesan', 'pending')->count();
            $sedangDisewa = Order::where('status_pesan', 'dikonfirmasi')->count();
            $pendapatanSelesai = Order::where('status_pesan', 'selesai')->sum('total_harga');

            return view('pesanan', compact(
                'orders',
                'totalOrders',
                'perluDikonfirmasi',
                'sedangDisewa',
                'pendapatanSelesai'
            ));
        })->name('pesanan.index');

        Route::patch('/pesanan/{id}/status', [OrderController::class, 'updateStatus'])->name('pesanan.status');
    });

    // RIWAYAT PESANAN CUSTOMER (BUG-008)
    Route::get('/riwayat-pesanan', [OrderController::class, 'customerHistory'])->name('riwayat.pesanan');
});

// KOLEKSI
Route::get('/koleksi', [CollectionController::class, 'index'])->name('koleksi.index');

// DETAIL PRODUK
Route::get('/detail/{id?}', [CollectionController::class, 'detail'])->name('detail.show');

// KERANJANG
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'addItem'])->name('cart.add');
Route::patch('/keranjang/update/{id}', [CartController::class, 'updateItem'])->name('cart.update');
Route::put('/keranjang/update/{id}', [CartController::class, 'updateItem']);
Route::delete('/keranjang/hapus/{id}', [CartController::class, 'removeItem'])->name('cart.remove');

// CHECKOUT PESANAN
Route::post('/pesanan/checkout', [OrderController::class, 'checkout'])->name('order.checkout');

// TENTANG
Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

// KONTAK
Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

// PEMESANAN 
Route::get('/pemesanan', function () {
    return view('pemesanan');
})->name('pemesanan');

// API Kategori (pencarian autocomplete dengan browser caching)
Route::get('/api/kategori', function () {
    return response()->json(
        \App\Models\Category::select('id', 'nama_kategori', 'slug')->get()
    )->header('Cache-Control', 'public, max-age=3600');
});

// ALIAS ADMIN & SHORTCUT
Route::get('/admin', function () {
    return Auth::check() && Auth::user()->isAdmin() ? redirect()->route('dashboard') : redirect()->route('login');
});
Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login');

// DEV ONLY: CSS Hot Reload Poller
if (app()->environment('local', 'testing')) {
    Route::get('/_css-dev/status', [\App\Http\Controllers\Dev\CssHotReloadController::class, 'status'])->name('dev.css.status');
}

require __DIR__.'/auth.php';
