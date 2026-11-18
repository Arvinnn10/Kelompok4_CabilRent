<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Basket;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

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

        // DASHBOARD
        Route::get('/dashboard', function () {
            $sewaBerjalan = Order::where('status_pesan', 'dikonfirmasi')->count();
            $pesananAktif = Order::whereIn('status_pesan', ['pending', 'dikonfirmasi'])->count();
            $totalProduk = Product::count();
            $produkTersedia = Product::where('status_produk', 'tersedia')->count();
            $totalKategori = Category::count();
            $terlambatKembali = 0;

            $kategoriList = Category::withCount('products')->get();
            $pesananTerbaru = Order::with(['basket.user', 'product', 'user'])->latest()->take(5)->get();
            $keranjangTerbaru = Basket::with(['product', 'user', 'order'])->latest()->take(5)->get();

            return view('dashboard', compact(
                'sewaBerjalan',
                'pesananAktif',
                'totalProduk',
                'produkTersedia',
                'totalKategori',
                'terlambatKembali',
                'kategoriList',
                'pesananTerbaru',
                'keranjangTerbaru'
            ));
        })->name('dashboard');


        // KATEGORI
        Route::get('/kategori', function () {
            $categories = Category::withCount('products')->get();
            return view('kategori', compact('categories'));
        })->name('kategori.index');

        Route::post('/kategori', function (Request $request) {
            $request->validate([
                'nama_kategori' => 'required|max:50',
            ]);

            Category::create([
                'nama_kategori' => $request->nama_kategori,
                'slug'          => Str::slug($request->nama_kategori),
            ]);

            return back()->with('success', 'Kategori berhasil ditambahkan!');
        })->name('kategori.store');

        Route::delete('/kategori/{id}', function ($id) {
            $category = Category::findOrFail($id);
            $category->delete();

            return back()->with('success', 'Kategori berhasil dihapus!');
        })->name('kategori.destroy');


        // PRODUK ADMIN
        Route::get('/produk', function (Request $request) {
            $categories = Category::all();
            $query = Product::with('category');

            if ($request->filled('kategori') && $request->kategori !== 'Semua') {
                $query->where('kategori_id', $request->kategori);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('nama_produk', 'like', "%{$search}%");
            }

            $products = $query->latest()->paginate(12)->withQueryString();
            $totalProducts = Product::count();
            $availableProducts = Product::where('status_produk', 'tersedia')->count();

            return view('produk', compact('products', 'categories', 'totalProducts', 'availableProducts'));
        })->name('produk.index');

        Route::post('/produk', function (Request $request) {
            $request->validate([
                'nama_produk'   => 'required|max:100',
                'kategori_id'   => 'required|exists:categories,id',
                'harga_sewa'    => 'required|numeric|min:0',
                'desk_produk'   => 'nullable|string',
                'status_produk' => 'required|in:tersedia,tidak_tersedia',
            ]);

            $fotoName = 'koleksi/kebaya-coco.png';
            if ($request->hasFile('foto_produk')) {
                $file = $request->file('foto_produk');
                $fotoName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/koleksi'), $fotoName);
                $fotoName = 'koleksi/' . $fotoName;
            }

            Product::create([
                'nama_produk'   => $request->nama_produk,
                'slug'          => Str::slug($request->nama_produk),
                'kategori_id'   => $request->kategori_id,
                'harga_sewa'    => $request->harga_sewa,
                'desk_produk'   => $request->desk_produk,
                'foto_produk'   => $fotoName,
                'status_produk' => $request->status_produk,
            ]);

            return back()->with('success', 'Produk berhasil ditambahkan!');
        })->name('produk.store');

        Route::patch('/produk/{id}/toggle', function ($id) {
            $product = Product::findOrFail($id);
            $product->status_produk = $product->status_produk === 'tersedia' ? 'tidak_tersedia' : 'tersedia';
            $product->save();

            return back()->with('success', 'Status ketersediaan produk berhasil diperbarui!');
        })->name('produk.toggle');

        Route::delete('/produk/{id}', function ($id) {
            $product = Product::findOrFail($id);
            $product->delete();

            return back()->with('success', 'Produk berhasil dihapus!');
        })->name('produk.destroy');


        // PESANAN ADMIN
        Route::get('/pesanan', function (Request $request) {
            $query = Order::with(['basket', 'product', 'user']);

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

            return view('pesanan', compact('orders', 'totalOrders'));
        })->name('pesanan.index');

        Route::patch('/pesanan/{id}/status', [OrderController::class, 'updateStatus'])->name('pesanan.status');


        // KERANJANG ADMIN
        Route::get('/admin/keranjang', function (Request $request) {
            $query = Basket::with(['product.category', 'user', 'order']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('nama_lengkap', 'like', "%{$search}%")
                              ->orWhere('username', 'like', "%{$search}%");
                        })
                        ->orWhereHas('product', function ($p) use ($search) {
                            $p->where('nama_produk', 'like', "%{$search}%");
                        });
                });
            }

            $baskets = $query->latest()->paginate(10)->withQueryString();
            $totalBaskets = Basket::count();

            return view('admin.keranjang', compact('baskets', 'totalBaskets'));
        })->name('admin.keranjang.index');

        Route::delete('/admin/keranjang/{id}', function ($id) {
            $basket = Basket::findOrFail($id);
            $basket->delete();

            return back()->with('success', 'Item keranjang berhasil dihapus!');
        })->name('admin.keranjang.destroy');
    });
});

// KOLEKSI
Route::get('/koleksi', [CollectionController::class, 'index'])->name('koleksi.index');

// DETAIL PRODUK
Route::get('/detail/{id?}', [CollectionController::class, 'detail'])->name('detail.show');

// KERANJANG
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'addItem'])->name('cart.add');
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

<<<<<<< HEAD
require __DIR__.'/auth.php';
=======
// KERANJANG
Route::get('/keranjang', function () {
    return view('keranjang');
});

// PEMESANAN
Route::get('/pemesanan', function () {
    return view('pemesanan');
});

// DETAIL PRODUK
Route::get('/detail', function () {
    return view('detail');
});

require __DIR__.'/auth.php';
>>>>>>> d0dfb69 (halaman pemesanan dan perbaikan halaman koleksi agar bisa masuk ke halaman whatsapp otomatis UPDATE V2)
