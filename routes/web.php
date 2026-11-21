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
                'deskripsi'     => 'nullable|string',
            ]);

            Category::create([
                'nama_kategori' => $request->nama_kategori,
                'slug'          => Str::slug($request->nama_kategori),
                'deskripsi'     => $request->deskripsi,
            ]);

            return back()->with('success', 'Kategori berhasil ditambahkan!');
        })->name('kategori.store');

        Route::put('/kategori/{id}', function (Request $request, $id) {
            $request->validate([
                'nama_kategori' => 'required|max:50',
                'deskripsi'     => 'nullable|string',
            ]);

            $category = Category::findOrFail($id);
            $category->update([
                'nama_kategori' => $request->nama_kategori,
                'slug'          => Str::slug($request->nama_kategori),
                'deskripsi'     => $request->deskripsi,
            ]);

            return back()->with('success', 'Kategori berhasil diperbarui!');
        })->name('kategori.update');

        Route::delete('/kategori/{id}', function ($id) {
            $category = Category::findOrFail($id);
            $category->delete();

            return back()->with('success', 'Kategori berhasil dihapus!');
        })->name('kategori.destroy');


        // PENGGUNA ADMIN
        Route::get('/pengguna', function (Request $request) {
            $query = User::where('role', '!=', 'admin')
                ->withCount('orders')
                ->withSum('orders', 'total_harga')
                ->with(['orders.product']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('no_telp', 'like', "%{$search}%");
                });
            }

            $users = $query->latest()->paginate(10)->withQueryString();
            $totalUsers = User::where('role', '!=', 'admin')->count();

            return view('pengguna', compact('users', 'totalUsers'));
        })->name('pengguna.index');

        Route::patch('/pengguna/{id}/blokir', function ($id) {
            $user = User::findOrFail($id);
            $user->status = ($user->status === 'diblokir') ? 'aktif' : 'diblokir';
            $user->save();

            $pesan = $user->status === 'diblokir'
                ? "Pengguna {$user->nama_lengkap} berhasil diblokir!"
                : "Pengguna {$user->nama_lengkap} berhasil diaktifkan kembali!";

            return back()->with('success', $pesan);
        })->name('pengguna.blokir');


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
            $stokMenipis = Product::where('stok', '>', 0)->where('stok', '<=', 2)->count();
            $stokHabis = Product::where('stok', '<=', 0)->count();

            return view('produk', compact(
                'products',
                'categories',
                'totalProducts',
                'availableProducts',
                'stokMenipis',
                'stokHabis'
            ));
        })->name('produk.index');

        Route::post('/produk', function (Request $request) {
            $request->validate([
                'nama_produk'   => 'required|max:100',
                'kategori_id'   => 'required|exists:categories,id',
                'harga_sewa'    => 'required|numeric|min:0',
                'ukuran'        => 'nullable|string|max:30',
                'stok'          => 'nullable|integer|min:0',
                'desk_produk'   => 'nullable|string',
                'status_produk' => 'required|in:tersedia,tidak_tersedia',
                'foto_produk'   => 'nullable|image|max:2048',
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
                'ukuran'        => $request->ukuran,
                'stok'          => $request->stok ?? 1,
                'desk_produk'   => $request->desk_produk,
                'foto_produk'   => $fotoName,
                'status_produk' => $request->status_produk,
            ]);

            return back()->with('success', 'Produk berhasil ditambahkan!');
        })->name('produk.store');

        Route::put('/produk/{id}', function (Request $request, $id) {
            $request->validate([
                'nama_produk'   => 'required|max:100',
                'kategori_id'   => 'required|exists:categories,id',
                'harga_sewa'    => 'required|numeric|min:0',
                'ukuran'        => 'nullable|string|max:30',
                'stok'          => 'nullable|integer|min:0',
                'desk_produk'   => 'nullable|string',
                'status_produk' => 'required|in:tersedia,tidak_tersedia',
                'foto_produk'   => 'nullable|image|max:2048',
            ]);

            $product = Product::findOrFail($id);

            $data = [
                'nama_produk'   => $request->nama_produk,
                'slug'          => Str::slug($request->nama_produk),
                'kategori_id'   => $request->kategori_id,
                'harga_sewa'    => $request->harga_sewa,
                'ukuran'        => $request->ukuran,
                'stok'          => $request->stok ?? 1,
                'desk_produk'   => $request->desk_produk,
                'status_produk' => $request->status_produk,
            ];

            if ($request->hasFile('foto_produk')) {
                $file = $request->file('foto_produk');
                $fotoName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/koleksi'), $fotoName);
                $data['foto_produk'] = 'koleksi/' . $fotoName;
            }

            $product->update($data);

            return back()->with('success', 'Produk berhasil diperbarui!');
        })->name('produk.update');

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

// PEMESANAN 
Route::get('/pemesanan', function () {
    return view('pemesanan');
})->name('pemesanan');

// API Kategori (pencarian autocomplete)
Route::get('/api/kategori', function () {
    return \App\Models\Category::select('id', 'nama_kategori', 'slug')->get();
});

require __DIR__.'/auth.php';
