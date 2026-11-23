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

                // Password acak aman untuk akun guest (BUG-010)
                $guestPassword = Str::password(16);

                $user = User::create([
                    'nama_lengkap' => $request->nama_lengkap,
                    'username'     => $username,
                    'email'        => 'user_' . $cleanPhone . '@cabilrent.id',
                    'no_telp'      => $request->no_whatsapp,
                    'password'     => Hash::make($guestPassword),
                    'role'         => 'customers',
                    'status'       => 'aktif',
                ]);
            }
        }

        $depositPerProduk = 100000;
        $totalSubtotal = 0;
        $totalDeposit = 0;
        $orderListText = [];
        $createdOrders = [];

        // Generate nomor invoice transaksi unik untuk grouping item (BUG-007)
        $kodePesanan = 'CR-INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

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

                if (!$product) {
                    DB::rollBack();
                    $namaItem = $item['nama'] ?? 'Item busana';
                    $pesan = "Produk '{$namaItem}' tidak ditemukan atau sudah tidak tersedia.";
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => $pesan], 422);
                    }
                    return back()->withErrors(['items' => $pesan]);
                }

                $itemJumlah = isset($item['jumlah']) ? (int)$item['jumlah'] : (isset($item['qty']) ? (int)$item['qty'] : 1);
                if ($itemJumlah < 1) {
                    DB::rollBack();
                    $pesan = "Jumlah keranjang untuk produk '{$product->nama_produk}' minimal 1 dan tidak boleh 0 atau negatif.";
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => $pesan], 422);
                    }
                    return back()->withErrors(['items' => $pesan]);
                }

                if ($product->status_produk !== 'tersedia' || $product->stok < $itemJumlah) {
                    DB::rollBack();
                    $pesan = "Produk '{$product->nama_produk}' saat ini sedang tidak tersedia atau stok tidak mencukupi (sisa: {$product->stok}).";
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => $pesan], 422);
                    }
                    return back()->withErrors(['items' => $pesan]);
                }

                $namaProduk = $product->nama_produk;
                $hargaSewa  = $product->harga_sewa;
                $productId  = $product->id;

                $subtotalProduk = $hargaSewa * $itemJumlah;
                $depositProduk  = $depositPerProduk * $itemJumlah;
                $orderTotal     = $subtotalProduk + $depositProduk;

                $totalSubtotal += $subtotalProduk;
                $totalDeposit  += $depositProduk;

                // Gunakan basket yang ada jika belum ter-checkout atau buat baru (BUG-006: Cegah keranjang yatim)
                $basket = Basket::where('user_id', $user->id)
                    ->where('produk_id', $productId)
                    ->whereDoesntHave('orders')
                    ->first();

                if ($basket) {
                    $basket->update([
                        'jumlah'        => $itemJumlah,
                        'tanggal_acara' => $request->tanggal_acara,
                    ]);
                } else {
                    $basket = Basket::create([
                        'user_id'       => $user->id,
                        'produk_id'     => $productId,
                        'jumlah'        => $itemJumlah,
                        'tanggal_acara' => $request->tanggal_acara,
                    ]);
                }

                $order = Order::create([
                    'kode_pesanan'   => $kodePesanan,
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
                $orderListText[] = "- {$namaProduk} x{$itemJumlah} (Rp " . number_format($hargaSewa, 0, ',', '.') . ")";
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
        $waAdminNumber = config('services.whatsapp.admin_number', '6287894302894');
        $daftarProdukStr = implode("\n", $orderListText);

        $waMessage = "*FORMAT SEWA CABIL.RENT*\n"
            . "No. Invoice: #{$kodePesanan}\n"
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
                'kode_pesanan' => $kodePesanan,
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

        $order = Order::with('basket.product')->findOrFail($id);
        $oldStatus = $order->status_pesan;
        $newStatus = $request->status_pesan;

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Status pesanan tidak berubah.');
        }

        $product = $order->basket?->product;
        $qty = $order->basket?->jumlah ?? 1;

        // 1. Jika pesanan dikonfirmasi (mulai disewa), potong stok
        if ($newStatus === 'dikonfirmasi' && $oldStatus !== 'dikonfirmasi') {
            if ($product) {
                if ($product->stok < $qty) {
                    return back()->with('error', "Gagal konfirmasi: Stok '{$product->nama_produk}' tidak mencukupi (sisa: {$product->stok}).");
                }
                $product->decrement('stok', $qty);
                if ($product->fresh()->stok <= 0) {
                    $product->update(['status_produk' => 'tidak_tersedia']);
                }
            }
        }

        // 2. Jika pesanan dibatalkan setelah sebelumnya dikonfirmasi, kembalikan stok
        if ($newStatus === 'dibatalkan' && $oldStatus === 'dikonfirmasi') {
            if ($product) {
                $product->increment('stok', $qty);
                if ($product->fresh()->stok > 0 && $product->status_produk === 'tidak_tersedia') {
                    $product->update(['status_produk' => 'tersedia']);
                }
            }
        }

        // 3. Jika pesanan selesai (masa sewa tuntas & barang kembali), pulihkan stok busana
        if ($newStatus === 'selesai' && $oldStatus === 'dikonfirmasi') {
            if ($product) {
                $product->increment('stok', $qty);
                if ($product->fresh()->stok > 0 && $product->status_produk === 'tidak_tersedia') {
                    $product->update(['status_produk' => 'tersedia']);
                }
            }
        }

        $order->status_pesan = $newStatus;
        $order->save();

        return back()->with('success', "Status pesanan #{$order->id} berhasil diupdate menjadi '{$newStatus}'!");
    }

    /**
     * Menampilkan riwayat pesanan pelanggan yang sedang login (BUG-008).
     */
    public function customerHistory(Request $request)
    {
        $userId = Auth::id();
        $orders = Order::whereHas('basket', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->with(['basket.product.category'])
        ->latest()
        ->get();

        // Mengelompokkan berdasarkan kode_pesanan atau format ID pesanan
        $groupedOrders = $orders->groupBy(function ($order) {
            return $order->kode_pesanan ?: ('CR-ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT));
        });

        $orderGroups = $groupedOrders;

        return view('riwayat-pesanan', compact('orders', 'orderGroups', 'groupedOrders'));
    }
}
