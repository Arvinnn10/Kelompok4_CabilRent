<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Tampilkan semua kategori (Admin)
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();

        // return view('admin.categories.index', compact('categories'));
        return response()->json($categories);
    }

    // Form tambah kategori (Admin)
    public function create()
    {
        // return view('admin.categories.create');
    }

    // Simpan kategori baru (Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:categories,nama_kategori',
        ]);

        $category = Category::create($validated);

        // return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
        return response()->json(['message' => 'Kategori berhasil ditambahkan.', 'data' => $category], 201);
    }

    // Form edit kategori (Admin)
    public function edit(Category $category)
    {
        // return view('admin.categories.edit', compact('category'));
        return response()->json($category);
    }

    // Update kategori (Admin)
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:categories,nama_kategori,' . $category->id,
        ]);

        $category->update($validated);

        // return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate.');
        return response()->json(['message' => 'Kategori berhasil diupdate.', 'data' => $category]);
    }

    // Hapus kategori (Admin)
    public function destroy(Category $category)
    {
        $category->delete();

        // return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
