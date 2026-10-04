<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
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

// ==================== AUTH (LOGIN, REGISTER, LOGOUT) ====================

// Halaman Login
Route::get('/login', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin() ? redirect('/dashboard') : redirect('/');
    }
    return view('auth.login');
})->name('login');

// Proses Login langsung lewat Model User
Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ], [
        'username.required' => 'Username atau email wajib diisi',
        'password.required' => 'Password wajib diisi',
    ]);

    // Cari user di model User berdasarkan username atau email
    $user = User::where('username', $request->username)
                ->orWhere('email', $request->username)
                ->first();

    // Cek password hash
    if ($user && Hash::check($request->password, $user->password)) {
        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended('/dashboard');
        }

        return redirect()->intended('/');
    }

    return back()->withErrors([
        'login' => 'Username atau password salah',
    ])->withInput();
})->name('login.post');

// Halaman Register
Route::get('/register', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin() ? redirect('/dashboard') : redirect('/');
    }
    return view('auth.register');
})->name('register');

// Proses Register langsung simpan lewat Model User (sebagai customer)
Route::post('/register', function (Request $request) {
    $request->validate([
        'nama_lengkap' => 'required|max:100',
        'username'     => 'required|max:50|unique:users,username',
        'email'        => 'required|email|max:100|unique:users,email',
        'no_telp'      => 'required|max:20|unique:users,no_telp',
        'password'     => 'required|min:4',
    ], [
        'nama_lengkap.required' => 'Nama lengkap wajib diisi',
        'username.required'     => 'Username wajib diisi',
        'username.unique'       => 'Username sudah terdaftar',
        'email.required'        => 'Email wajib diisi',
        'email.email'           => 'Format email tidak valid',
        'email.unique'          => 'Email sudah terdaftar',
        'no_telp.required'      => 'Nomor telepon wajib diisi',
        'no_telp.unique'        => 'Nomor telepon sudah terdaftar',
        'password.required'     => 'Password wajib diisi',
        'password.min'          => 'Password minimal 4 karakter',
    ]);

    User::create([
        'nama_lengkap' => $request->nama_lengkap,
        'username'     => $request->username,
        'email'        => $request->email,
        'no_telp'      => $request->no_telp,
        'password'     => Hash::make($request->password),
        'role'         => 'customers',
    ]);

    return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
})->name('register.post');

// ==================== ROUTES YANG MEMERLUKAN LOGIN (AUTH) ====================
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::match(['get', 'post'], '/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');


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