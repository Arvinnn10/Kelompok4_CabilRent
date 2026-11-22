<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $dbBaskets = collect();
        if (Auth::check()) {
            $dbBaskets = Basket::with(['product.category'])
                ->where('user_id', Auth::id())
                ->whereDoesntHave('orders')
                ->latest()
                ->get();
        }

        return view('keranjang', compact('dbBaskets'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'produk_id'     => 'required',
            'tanggal_acara' => 'nullable|date',
            'jumlah'        => 'nullable|integer|min:1',
        ], [
            'jumlah.min'     => 'Jumlah keranjang minimal 1 dan tidak boleh 0 atau negatif.',
            'jumlah.integer' => 'Jumlah keranjang harus berupa bilangan bulat.',
        ]);

        $identifier = $request->produk_id;
        $product = Product::where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        if (Auth::check()) {
            $jumlah = max(1, (int)($request->jumlah ?? 1));

            $basket = Basket::create([
                'user_id'       => Auth::id(),
                'produk_id'     => $product->id,
                'jumlah'        => $jumlah,
                'tanggal_acara' => $request->tanggal_acara ?? now()->addDays(3)->toDateString(),
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Berhasil masuk ke keranjang.',
                'basket_id' => $basket->id,
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ], [
            'jumlah.required' => 'Jumlah keranjang wajib diisi.',
            'jumlah.min'      => 'Jumlah keranjang minimal 1 dan tidak boleh 0 atau negatif.',
            'jumlah.integer'  => 'Jumlah keranjang harus berupa bilangan bulat.',
        ]);

        if (Auth::check()) {
            $basket = Basket::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if ($basket) {
                $basket->update([
                    'jumlah' => max(1, (int)$request->jumlah),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Jumlah keranjang berhasil diperbarui.',
                    'basket'  => $basket,
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Item keranjang tidak ditemukan.',
        ], 404);
    }

    public function removeItem($id)
    {
        if (Auth::check()) {
            $basket = Basket::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if ($basket) {
                if ($basket->orders()->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Item keranjang tidak dapat dihapus karena sudah memiliki riwayat pesanan.'
                    ], 400);
                }
                $basket->delete();
            }
        }

        return response()->json(['success' => true]);
    }
}
