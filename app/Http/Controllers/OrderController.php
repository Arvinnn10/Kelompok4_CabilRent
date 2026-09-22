<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Tampilkan semua pesanan (Admin) atau pesanan milik user (Customer)
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $orders = Order::with(['user', 'product'])->latest()->get();
        } else {
            $orders = Order::with('product')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        // return view('orders.index', compact('orders'));
        return response()->json($orders);
    }

    // Detail satu pesanan
    public function show(Order $order)
    {
        // Customer hanya bisa lihat pesanannya sendiri
        if (Auth::user()->isCustomer() && $order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        $order->load(['user', 'product.category']);

        // return view('orders.show', compact('order'));
        return response()->json($order);
    }

    // Buat pesanan baru (Customer)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id'      => 'required|exists:products,id',
            'no_whatsapp'    => 'required|string|max:20',
            'instagram'      => 'nullable|string|max:50',
            'domisili'       => 'required|string|max:100',
            'desk_pemesanan' => 'required|string|max:50',
            'total_harga'    => 'required|integer|min:0',
            'tb_bb'          => 'required|string|max:20',
        ]);

        // Cek produk tersedia
        $product = Product::findOrFail($validated['produk_id']);
        if ($product->status_produk !== 'tersedia') {
            return response()->json(['message' => 'Produk tidak tersedia.'], 422);
        }

        $order = Order::create([
            ...$validated,
            'user_id'     => Auth::id(),
            'status_pesan'=> 'pending',
        ]);

        // Hapus item dari keranjang setelah checkout
        Basket::where('user_id', Auth::id())
            ->where('produk_id', $validated['produk_id'])
            ->delete();

        // return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat.');
        return response()->json(['message' => 'Pesanan berhasil dibuat.', 'data' => $order], 201);
    }

    // Update status pesanan (Admin)
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status_pesan' => 'required|in:pending,dikonfirmasi,selesai,dibatalkan',
        ]);

        $order->update($validated);

        // return redirect()->route('orders.index')->with('success', 'Status pesanan diupdate.');
        return response()->json(['message' => 'Status pesanan diupdate.', 'data' => $order]);
    }

    // Batalkan pesanan (Customer — hanya jika masih pending)
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if ($order->status_pesan !== 'pending') {
            return response()->json(['message' => 'Pesanan tidak bisa dibatalkan.'], 422);
        }

        $order->update(['status_pesan' => 'dibatalkan']);

        // return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
        return response()->json(['message' => 'Pesanan berhasil dibatalkan.']);
    }
}
