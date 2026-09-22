<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Tampilkan semua produk (bisa diakses semua)
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->kategori, fn($q) => $q->where('kategori_id', $request->kategori))
            ->when($request->status,   fn($q) => $q->where('status_produk', $request->status))
            ->latest()
            ->get();

        // return view('products.index', compact('products'));
        return response()->json($products);
    }

    // Detail satu produk
    public function show(Product $product)
    {
        $product->load('category');

        // return view('products.show', compact('product'));
        return response()->json($product);
    }

    // Form tambah produk (Admin)
    public function create()
    {
        $categories = Category::all();

        // return view('admin.products.create', compact('categories'));
        return response()->json($categories);
    }

    // Simpan produk baru (Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:categories,id',
            'nama_produk'  => 'required|string|max:100',
            'desk_produk'  => 'nullable|string',
            'harga_sewa'   => 'required|integer|min:0',
            'foto_produk'  => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status_produk'=> 'required|in:tersedia,tidak_tersedia',
            'foto_kebaya'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload foto produk
        $validated['foto_produk'] = $request->file('foto_produk')->store('products', 'public');

        // Upload foto kebaya jika ada
        if ($request->hasFile('foto_kebaya')) {
            $validated['foto_kebaya'] = $request->file('foto_kebaya')->store('products', 'public');
        }

        $product = Product::create($validated);

        // return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
        return response()->json(['message' => 'Produk berhasil ditambahkan.', 'data' => $product], 201);
    }

    // Form edit produk (Admin)
    public function edit(Product $product)
    {
        $categories = Category::all();

        // return view('admin.products.edit', compact('product', 'categories'));
        return response()->json(['product' => $product, 'categories' => $categories]);
    }

    // Update produk (Admin)
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:categories,id',
            'nama_produk'  => 'required|string|max:100',
            'desk_produk'  => 'nullable|string',
            'harga_sewa'   => 'required|integer|min:0',
            'foto_produk'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status_produk'=> 'required|in:tersedia,tidak_tersedia',
            'foto_kebaya'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Update foto produk jika ada file baru
        if ($request->hasFile('foto_produk')) {
            Storage::disk('public')->delete($product->foto_produk);
            $validated['foto_produk'] = $request->file('foto_produk')->store('products', 'public');
        }

        // Update foto kebaya jika ada file baru
        if ($request->hasFile('foto_kebaya')) {
            Storage::disk('public')->delete($product->foto_kebaya);
            $validated['foto_kebaya'] = $request->file('foto_kebaya')->store('products', 'public');
        }

        $product->update($validated);

        // return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
        return response()->json(['message' => 'Produk berhasil diupdate.', 'data' => $product]);
    }

    // Hapus produk (Admin)
    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->foto_produk);
        if ($product->foto_kebaya) {
            Storage::disk('public')->delete($product->foto_kebaya);
        }

        $product->delete();

        // return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}
