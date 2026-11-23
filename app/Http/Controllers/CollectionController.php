<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $query = Product::with('category')->where('status_produk', 'tersedia');

        $kategoriParam = $request->query('kategori');
        if ($kategoriParam && $kategoriParam !== 'semua') {
            $query->whereHas('category', function ($q) use ($kategoriParam) {
                $q->where('slug', $kategoriParam)
                  ->orWhere('nama_kategori', 'like', "%{$kategoriParam}%");
            });
        }

        $search = $request->query('search') ?? $request->query('cari') ?? $request->query('q');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('desk_produk', 'like', "%{$search}%");
            });
        }

        $urut = $request->query('urut', 'terbaru');
        if ($urut === 'harga-terendah') {
            $query->orderBy('harga_sewa', 'asc');
        } elseif ($urut === 'harga-tertinggi') {
            $query->orderBy('harga_sewa', 'desc');
        } elseif ($urut === 'populer') {
            $query->withCount('baskets')->orderByDesc('baskets_count');
        } else {
            $query->latest();
        }

        $products = $query->get();
        $totalProducts = $products->count();

        return response()
            ->view('koleksi', compact(
                'categories',
                'products',
                'totalProducts',
                'kategoriParam',
                'urut',
                'search'
            ))
            ->header('Cache-Control', 'no-cache, private, must-revalidate');
    }

    public function detail(Request $request, $id = null)
    {
        $identifier = $id ?? $request->query('id');

        $product = null;
        if (!empty($identifier)) {
            // Pencarian teroptimasi indeks: utamakan primary key jika numerik, atau slug unik
            if (is_numeric($identifier)) {
                $product = Product::with('category')->find((int)$identifier);
            }
            if (!$product) {
                $product = Product::with('category')->where('slug', $identifier)->first();
            }
            if (!$product) {
                $product = Product::with('category')->where('nama_produk', 'like', "%{$identifier}%")->first();
            }
        }

        if (!$product) {
            $product = Product::with('category')->firstOrFail();
        }

        return response()
            ->view('detail', compact('product'))
            ->header('Cache-Control', 'no-cache, private, must-revalidate');
    }
}
