<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    // Tampilkan keranjang milik user yang login
    public function index()
    {
        $baskets = Basket::with('product.category')
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->get();

        // return view('baskets.index', compact('baskets'));
        return response()->json($baskets);
    }

    // Tambah produk ke keranjang
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id'     => 'required|exists:products,id',
            'jumlah'        => 'required|integer|min:1',
            'tanggal_acara' => 'required|date|after_or_equal:today',
        ]);

        // Cek apakah produk tersedia
        $product = Product::findOrFail($validated['produk_id']);
        if ($product->status_produk !== 'tersedia') {
            return response()->json(['message' => 'Produk tidak tersedia.'], 422);
        }

        // Cek apakah produk sudah ada di keranjang
        $existing = Basket::where('user_id', Auth::id())
            ->where('produk_id', $validated['produk_id'])
            ->where('tanggal_acara', $validated['tanggal_acara'])
            ->first();

        if ($existing) {
            $existing->increment('jumlah', $validated['jumlah']);
            $basket = $existing;
        } else {
            $basket = Basket::create([
                'user_id'       => Auth::id(),
                'produk_id'     => $validated['produk_id'],
                'jumlah'        => $validated['jumlah'],
                'tanggal_acara' => $validated['tanggal_acara'],
                'created_at'    => now(),
            ]);
        }

        // return redirect()->route('basket.index')->with('success', 'Produk ditambahkan ke keranjang.');
        return response()->json(['message' => 'Produk ditambahkan ke keranjang.', 'data' => $basket], 201);
    }

    // Hapus item dari keranjang
    public function destroy(Basket $basket)
    {
        // Pastikan hanya pemilik yang bisa hapus
        if ($basket->user_id !== Auth::id()) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $basket->delete();

        // return redirect()->route('basket.index')->with('success', 'Item dihapus dari keranjang.');
        return response()->json(['message' => 'Item dihapus dari keranjang.']);
    }
}
