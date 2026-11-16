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

        return view('koleksi', compact(
            'categories',
            'products',
            'totalProducts',
            'kategoriParam',
            'urut',
            'search'
        ));
    }

    public function detail(Request $request, $id = null)
    {
        $identifier = $id ?? $request->query('id');

        $product = null;
        if (!empty($identifier)) {
            $product = Product::with('category')
                ->where('slug', $identifier)
                ->orWhere('id', $identifier)
                ->orWhere('nama_produk', 'like', "%{$identifier}%")
                ->first();
        }

        if (!$product) {
            $product = Product::with('category')->firstOrFail();
        }

        $relatedProducts = Product::with('category')
            ->where('id', '!=', $product->id)
            ->where('kategori_id', $product->kategori_id)
            ->where('status_produk', 'tersedia')
            ->take(4)
            ->get();

        return view('detail', compact('product', 'relatedProducts'));
    }
}
