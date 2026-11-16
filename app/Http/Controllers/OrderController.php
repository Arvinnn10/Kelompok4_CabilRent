<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:100',
            'no_whatsapp'   => 'required|string|max:25',
            'domisili'      => 'required|string|max:100',
            'instagram'     => 'nullable|string|max:50',
            'tb_bb'         => 'required|string|max:30',
            'tanggal_acara' => 'required|date',
            'items'         => 'nullable',
        ]);

        $rawItems = $request->input('items');
        if (is_string($rawItems)) {
            $rawItems = json_decode($rawItems, true);
        }

        if (empty($rawItems) || !is_array($rawItems)) {
            return back()->withErrors(['items' => 'Keranjang masih kosong.']);
        }

        $user = Auth::user();
        if (!$user) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->no_whatsapp);
            $user = User::where('no_telp', $request->no_whatsapp)
                ->orWhere('no_telp', $cleanPhone)
                ->first();

            if (!$user) {
                $suffix = substr($cleanPhone, -4) ?: rand(1000, 9999);
                $username = 'cust_' . Str::slug($request->nama_lengkap, '_') . '_' . $suffix;
                if (User::where('username', $username)->exists()) {
                    $username .= '_' . rand(10, 99);
                }

                $user = User::create([
                    'nama_lengkap' => $request->nama_lengkap,
                    'username'     => $username,
                    'email'        => 'user_' . $cleanPhone . '@cabilrent.id',
                    'no_telp'      => $request->no_whatsapp,
                    'password'     => Hash::make('cabil_' . $suffix),
                    'role'         => 'customers',
                ]);
            }
        }

        $depositPerProduk = 100000;
        $totalSubtotal = 0;
        $totalDeposit = 0;
        $orderListText = [];
        $createdOrders = [];

        DB::beginTransaction();
        try {
            foreach ($rawItems as $item) {
                $itemId = $item['id'] ?? null;
                $product = null;
                if ($itemId) {
                    $product = Product::where('slug', $itemId)
                        ->orWhere('id', $itemId)
                        ->first();
                }

                $namaProduk = $product ? $product->nama_produk : ($item['nama'] ?? 'Busana Sewa');
                $hargaSewa = $product ? $product->harga_sewa : (int)($item['harga'] ?? 0);
                $productId = $product ? $product->id : Product::first()->id;

                $totalSubtotal += $hargaSewa;
                $totalDeposit += $depositPerProduk;
                $orderTotal = $hargaSewa + $depositPerProduk;

                $basket = Basket::create([
                    'user_id'       => $user->id,
                    'produk_id'     => $productId,
                    'jumlah'        => 1,
                    'tanggal_acara' => $request->tanggal_acara,
                ]);

                $order = Order::create([
                    'basket_id'      => $basket->id,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'instagram'      => $request->instagram ?: '-',
                    'domisili'       => $request->domisili,
                    'desk_pemesanan' => $namaProduk . ' (Acara: ' . $request->tanggal_acara . ')',
                    'total_harga'    => $orderTotal,
                    'status_pesan'   => 'pending',
                    'tb_bb'          => $request->tb_bb,
                ]);

                $createdOrders[] = $order;
                $orderListText[] = "- {$namaProduk} (Rp " . number_format($hargaSewa, 0, ',', '.') . ")";
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses pesanan: ' . $e->getMessage(),
                ], 500);
            }
            return back()->withErrors(['order' => 'Gagal memproses pesanan: ' . $e->getMessage()]);
        }

        $grandTotal = $totalSubtotal + $totalDeposit;
        $firstOrderId = $createdOrders[0]->id ?? 1;

        $waAdminNumber = '6287894302894';
        $daftarProdukStr = implode("\n", $orderListText);

        $waMessage = "*FORMAT SEWA CABIL.RENT*\n"
            . "No. Pesanan: #{$firstOrderId}\n"
            . "Nama: {$request->nama_lengkap}\n"
            . "No. WA: {$request->no_whatsapp}\n"
            . "Domisili: {$request->domisili}\n"
            . "Instagram: " . ($request->instagram ?: '-') . "\n"
            . "TB/BB: {$request->tb_bb}\n"
            . "Tanggal Acara: {$request->tanggal_acara}\n\n"
            . "*Busana:*\n"
            . "{$daftarProdukStr}\n\n"
            . "Subtotal Sewa: Rp " . number_format($totalSubtotal, 0, ',', '.') . "\n"
            . "Deposit: Rp " . number_format($totalDeposit, 0, ',', '.') . "\n"
            . "Total Awal: Rp " . number_format($grandTotal, 0, ',', '.') . "\n\n"
            . "Halo admin cabil.rent, saya mau sewa busana ini. Apakah masih tersedia?";

        $whatsappUrl = 'https://wa.me/' . $waAdminNumber . '?text=' . urlencode($waMessage);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'      => true,
                'order_ids'    => array_map(fn($o) => $o->id, $createdOrders),
                'whatsapp_url' => $whatsappUrl,
            ]);
        }

        return redirect()->away($whatsappUrl);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pesan' => 'required|in:pending,dikonfirmasi,selesai,dibatalkan',
        ]);

        $order = Order::findOrFail($id);
        $order->status_pesan = $request->status_pesan;
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }
}
