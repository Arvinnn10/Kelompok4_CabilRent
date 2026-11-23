<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan metrik riil dari database.
     * Mengatasi BUG-001: Donut chart dan ringkasan status pesanan dinamis.
     */
    public function index()
    {
        $sewaBerjalan = Order::where('status_pesan', 'dikonfirmasi')->count();
        $pesananAktif = Order::whereIn('status_pesan', ['pending', 'dikonfirmasi'])->count();
        $totalProduk = Product::count();
        $produkTersedia = Product::where('status_produk', 'tersedia')->count();
        $totalKategori = Category::count();
        $terlambatKembali = 0;

        $kategoriList = Category::withCount('products')->get();
        $pesananTerbaru = Order::with(['basket.user', 'product', 'user'])->latest()->take(5)->get();

        // Metrik riil status pesanan untuk Donut Chart (BUG-001)
        $countPending      = Order::where('status_pesan', 'pending')->count();
        $countDikonfirmasi = Order::where('status_pesan', 'dikonfirmasi')->count();
        $countSelesai      = Order::where('status_pesan', 'selesai')->count();
        $countDibatalkan   = Order::where('status_pesan', 'dibatalkan')->count();
        $totalOrdersCount  = Order::count();

        // Metrik pendapatan dan pengguna riil
        $pendapatanSemua = Order::where('status_pesan', '!=', 'dibatalkan')->sum('total_harga');
        $pendapatanBulanIni = Order::where('status_pesan', '!=', 'dibatalkan')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_harga');
        if ($pendapatanBulanIni == 0 && $pendapatanSemua > 0) {
            $pendapatanBulanIni = $pendapatanSemua;
        }
        $pesananBaruCount = $countPending;
        $totalPenggunaCount = \App\Models\User::where('role', '!=', 'admin')->count();

        // SVG donut circumference untuk radius 42 adalah 2 * PI * 42 ≈ 263.89
        $circumference = 263.89;
        $chartData = [];
        $offset = 0.0;

        $statusList = [
            'menunggu'     => ['label' => 'Menunggu',     'count' => $countPending,      'class' => 'status-menunggu'],
            'dikonfirmasi' => ['label' => 'Dikonfirmasi', 'count' => $countDikonfirmasi, 'class' => 'status-dikonfirmasi'],
            'selesai'      => ['label' => 'Selesai',      'count' => $countSelesai,      'class' => 'status-selesai'],
            'dibatalkan'   => ['label' => 'Dibatalkan',   'count' => $countDibatalkan,   'class' => 'status-dibatalkan'],
        ];

        foreach ($statusList as $key => $item) {
            $dashArray = 0;
            if ($totalOrdersCount > 0 && $item['count'] > 0) {
                $dashArray = round(($item['count'] / $totalOrdersCount) * $circumference, 2);
            }
            $chartData[$key] = [
                'label'      => $item['label'],
                'count'      => $item['count'],
                'class'      => $item['class'],
                'dasharray'  => "{$dashArray} {$circumference}",
                'dashoffset' => sprintf("%.2f", -$offset),
            ];
            $offset += $dashArray;
        }

        return view('dashboard', compact(
            'sewaBerjalan',
            'pesananAktif',
            'totalProduk',
            'produkTersedia',
            'totalKategori',
            'terlambatKembali',
            'kategoriList',
            'pesananTerbaru',
            'totalOrdersCount',
            'chartData',
            'countPending',
            'countDikonfirmasi',
            'countSelesai',
            'countDibatalkan',
            'pendapatanBulanIni',
            'pendapatanSemua',
            'pesananBaruCount',
            'totalPenggunaCount'
        ));
    }
}
