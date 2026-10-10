<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

// Halaman utama (Home / Dashboard Customer)
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return redirect()->route('home');
});

// ==================== ROUTES YANG MEMERLUKAN LOGIN (AUTH) ====================
Route::middleware(['auth'])->group(function () {


    // ==================== ROUTES KHUSUS ADMIN ====================
    Route::middleware(['admin'])->group(function () {

        // ==================== DASHBOARD ====================

        Route::get('/dashboard', function () {
            $sewaBerjalan = Order::where('status_pesan', 'dikonfirmasi')->count();
            $pesananAktif = Order::whereIn('status_pesan', ['pending', 'dikonfirmasi'])->count();
            $totalProduk = Product::count();
            $produkTersedia = Product::where('status_produk', 'tersedia')->count();
            $totalKategori = Category::count();
            $terlambatKembali = 0;

            $kategoriList = Category::withCount('products')->get();
            $pesananTerbaru = Order::with(['basket', 'product', 'user'])->latest()->take(5)->get();

            return view('dashboard', compact(
                'sewaBerjalan',
                'pesananAktif',
                'totalProduk',
                'produkTersedia',
                'totalKategori',
                'terlambatKembali',
                'kategoriList',
                'pesananTerbaru'
            ));
        })->name('dashboard');


        // ==================== KATEGORI ====================

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
            ]);

            return back()->with('success', 'Kategori berhasil ditambahkan!');
        })->name('kategori.store');

        Route::delete('/kategori/{id}', function ($id) {
            $category = Category::findOrFail($id);
            $category->delete();

            return back()->with('success', 'Kategori berhasil dihapus!');
        })->name('kategori.destroy');


        // ==================== PRODUK ====================

        Route::get('/produk', function () {
            return view('produk');
        })->name('produk.index');


        // ==================== PESANAN ====================

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
    });
});

require __DIR__.'/auth.php';