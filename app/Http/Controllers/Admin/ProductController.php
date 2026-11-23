<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Generate unique slug helper
     */
    protected function generateUniqueSlug($title, $ignoreId = null)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug ?: 'produk';
        $count = 1;
        while (Product::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }
        return $slug;
    }

    public function index(Request $request)
    {
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
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'   => 'required|string|max:100',
            'kategori_id'   => 'required|exists:categories,id',
            'harga_sewa'    => 'required|numeric|min:0',
            'ukuran'        => 'nullable|string|max:30',
            'stok'          => 'nullable|integer|min:0',
            'desk_produk'   => 'nullable|string',
            'status_produk' => 'required|in:tersedia,tidak_tersedia',
            'foto_produk'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama_produk.required'   => 'Nama produk wajib diisi.',
            'kategori_id.required'   => 'Kategori wajib dipilih.',
            'kategori_id.exists'     => 'Kategori tidak valid.',
            'harga_sewa.required'    => 'Harga sewa wajib diisi.',
            'status_produk.required' => 'Status produk wajib dipilih.',
            'foto_produk.image'      => 'File foto harus berupa gambar valid (jpeg, png, jpg, webp).',
            'foto_produk.max'        => 'Ukuran foto maksimal 2MB.',
        ]);

        // Sanitasi file upload aman (BUG-013)
        $fotoName = 'koleksi/kebaya-coco.png';
        if ($request->hasFile('foto_produk')) {
            $file = $request->file('foto_produk');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $safeName = time() . '_' . Str::random(12) . '.' . strtolower($extension);
            $file->move(public_path('images/koleksi'), $safeName);
            $fotoName = 'koleksi/' . $safeName;
        }

        Product::create([
            'nama_produk'   => $request->nama_produk,
            'slug'          => $this->generateUniqueSlug($request->nama_produk),
            'kategori_id'   => $request->kategori_id,
            'harga_sewa'    => $request->harga_sewa,
            'ukuran'        => $request->ukuran,
            'stok'          => $request->stok ?? 1,
            'desk_produk'   => $request->desk_produk,
            'foto_produk'   => $fotoName,
            'status_produk' => $request->status_produk,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk'   => 'required|string|max:100',
            'kategori_id'   => 'required|exists:categories,id',
            'harga_sewa'    => 'required|numeric|min:0',
            'ukuran'        => 'nullable|string|max:30',
            'stok'          => 'nullable|integer|min:0',
            'desk_produk'   => 'nullable|string',
            'status_produk' => 'required|in:tersedia,tidak_tersedia',
            'foto_produk'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama_produk.required'   => 'Nama produk wajib diisi.',
            'kategori_id.required'   => 'Kategori wajib dipilih.',
            'harga_sewa.required'    => 'Harga sewa wajib diisi.',
            'foto_produk.image'      => 'File foto harus berupa gambar valid (jpeg, png, jpg, webp).',
            'foto_produk.max'        => 'Ukuran foto maksimal 2MB.',
        ]);

        $product = Product::findOrFail($id);

        $data = [
            'nama_produk'   => $request->nama_produk,
            'slug'          => $this->generateUniqueSlug($request->nama_produk, $id),
            'kategori_id'   => $request->kategori_id,
            'harga_sewa'    => $request->harga_sewa,
            'ukuran'        => $request->ukuran,
            'stok'          => $request->stok ?? 1,
            'desk_produk'   => $request->desk_produk,
            'status_produk' => $request->status_produk,
        ];

        if ($request->hasFile('foto_produk')) {
            $file = $request->file('foto_produk');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $safeName = time() . '_' . Str::random(12) . '.' . strtolower($extension);
            $file->move(public_path('images/koleksi'), $safeName);

            // Bersihkan file foto lama jika bukan default dan tidak digunakan produk lain (BUG-013)
            $oldFoto = $product->foto_produk;
            if ($oldFoto && !str_starts_with($oldFoto, 'http')) {
                $stillUsed = Product::where('id', '!=', $product->id)->where('foto_produk', $oldFoto)->exists();
                if (!$stillUsed && !str_contains($oldFoto, 'kebaya-coco.png')) {
                    $oldPath = public_path('images/' . $oldFoto);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
            }

            $data['foto_produk'] = 'koleksi/' . $safeName;
        }

        $product->update($data);

        return back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status_produk = ($product->status_produk === 'tersedia') ? 'tidak_tersedia' : 'tersedia';
        $product->save();

        return back()->with('success', 'Status ketersediaan produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dengan proteksi foreign key integrity (BUG-014).
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Cek apakah produk memiliki riwayat transaksi/pesanan
        $hasOrders = $product->orders()->exists() || $product->baskets()->whereHas('orders')->exists();

        if ($hasOrders) {
            // Hindari 500 QueryException: Ubah status menjadi tidak_tersedia (Soft Protect)
            $product->update(['status_produk' => 'tidak_tersedia']);
            return back()->with('error', "Produk '{$product->nama_produk}' tidak dapat dihapus permanen karena memiliki riwayat pesanan. Status otomatis diubah menjadi 'tidak_tersedia'.");
        }

        try {
            // Hapus file fisik gambar jika aman
            $foto = $product->foto_produk;
            if ($foto && !str_starts_with($foto, 'http')) {
                $otherUsed = Product::where('id', '!=', $product->id)->where('foto_produk', $foto)->exists();
                if (!$otherUsed && !str_contains($foto, 'kebaya-coco.png')) {
                    $fotoPath = public_path('images/' . $foto);
                    if (file_exists($fotoPath)) {
                        @unlink($fotoPath);
                    }
                }
            }

            // Hapus keranjang belum tercheckout jika ada
            $product->baskets()->delete();
            $product->delete();

            return back()->with('success', "Produk '{$product->nama_produk}' berhasil dihapus!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
