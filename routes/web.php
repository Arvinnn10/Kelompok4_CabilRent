<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;
use App\Models\Basket;
use App\Models\Order;

Route::get('/', function () {
    return view('welcome');
});

// Auth / Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


// Produk
Route::get('/produk', function () {
    $products = Product::with('category')->latest()->get();
    return view('products.index', compact('products'));
})->name('produk.index');

Route::get('/produk/{id}', function ($id) {
    $product = Product::with('category')->findOrFail($id);
    return view('products.show', compact('product'));
})->name('produk.show');

// Kategori
Route::get('/kategori', function () {
    $categories = Category::all();
    return view('categories.index', compact('categories'));
})->name('kategori.index');

// Keranjang
Route::get('/keranjang', function () {
    $baskets = Basket::with('product')->get();
    return view('baskets.index', compact('baskets'));
})->name('keranjang.index');

// Pesanan
Route::get('/pesanan', function () {
    $orders = Order::with(['product', 'user'])->latest()->get();
    return view('orders.index', compact('orders'));
})->name('pesanan.index');