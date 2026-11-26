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
        // Pelanggan belum login tidak bisa membeli/memesan sewa, harus login terlebih dahulu
        if (!Auth::check()) {
            $pesan = 'Silakan masuk (login) terlebih dahulu untuk melanjutkan pemesanan sewa.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => false,
                    'message'  => $pesan,
                    'redirect' => route('login'),
                ], 401);
            }
            return redirect()->guest(route('login'))->with('warning', $pesan);
        }

        $request->validate([
            'nama_lengkap'         => 'required|string|max:100',
            'no_whatsapp'          => 'required|string|max:20',
            'domisili'             => 'required|string|max:100',
            'instagram'            => 'nullable|string|max:50',
            'tb_bb'                => 'required|string|max:30',
            'tanggal_acara'        => 'required|date',
            'items'                => 'nullable',
            'confirmed_adjustment' => 'nullable|boolean',
        ]);

        $rawItems = $request->input('items');
        if (is_string($rawItems)) {
            $rawItems = json_decode($rawItems, true);
        }

        if (empty($rawItems) || !is_array($rawItems)) {
            $pesan = 'Keranjang masih kosong.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }
            return back()->withErrors(['items' => $pesan]);
        }

        // Aturan A: Penggabungan produk duplikat sebelum pemrosesan
        // Jika customer mengirim produk yang sama beberapa kali, gabungkan menjadi satu produk & jumlahkan
        $aggregatedMap = []; // productId => ['product' => Product, 'requested_qty' => int, 'raw_item' => array]
        foreach ($rawItems as $item) {
            $itemId = $item['id'] ?? null;
            if (!$itemId) {
                continue;
            }

            $product = Product::where('id', $itemId)
                ->orWhere('slug', $itemId)
                ->first();

            if (!$product) {
                continue;
            }

            $hasJumlah = array_key_exists('jumlah', $item);
            $hasQty = array_key_exists('qty', $item);

            if (!$hasJumlah && !$hasQty) {
                // Jika produk baru ditambahkan dan parameter jumlah/qty belum tersedia, otomatis default 1
                $qty = 1;
            } else {
                $rawQty = $hasJumlah ? $item['jumlah'] : $item['qty'];

                // Validasi jumlah pesanan: tolak null, kosong, non-numerik, angka <= 0, atau desimal
                if ($rawQty === null || $rawQty === '' || !is_numeric($rawQty) || (int)$rawQty <= 0 || (float)$rawQty != (int)$rawQty) {
                    $pesan = "Jumlah pesanan untuk produk '{$product->nama_produk}' tidak valid. Jumlah harus berupa bilangan bulat positif minimal 1.";
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $pesan,
                            'errors'  => ['jumlah' => [$pesan]],
                        ], 422);
                    }
                    return back()->withErrors(['items' => $pesan]);
                }

                $qty = (int)$rawQty;
            }

            if (!isset($aggregatedMap[$product->id])) {
                $aggregatedMap[$product->id] = [
                    'product'       => $product,
                    'requested_qty' => $qty,
                    'raw_item'      => $item,
                ];
            } else {
                $aggregatedMap[$product->id]['requested_qty'] += $qty;
            }
        }

        if (empty($aggregatedMap)) {
            $pesan = 'Tidak ada produk valid yang ditemukan dalam keranjang.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $pesan], 422);
            }
            return back()->withErrors(['items' => $pesan]);
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
        $isConfirmed = $request->boolean('confirmed_adjustment');

        // Aturan D: Transaksi database dengan penguncian baris (lockForUpdate) untuk mencegah overselling
        $result = DB::transaction(function () use (
            $request,
            $aggregatedMap,
            $user,
            $depositPerProduk,
            $isConfirmed
        ) {
            $productIds = array_keys($aggregatedMap);
            // Kunci baris produk saat transaksi berjalan
            $lockedProducts = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $adjustments = [];
            $itemsToProcess = [];
            $allUnavailable = true;

            foreach ($aggregatedMap as $pId => $agg) {
                $product = $lockedProducts->get($pId);
                if (!$product) {
                    continue;
                }

                $reqQty = $agg['requested_qty'];
                $availStock = max(0, (int)$product->stok);
                $isAvailable = ($product->status_produk === 'tersedia' && $availStock > 0);

                if (!$isAvailable) {
                    // Aturan C: Produk dengan stok 0 atau status tidak tersedia otomatis dilewati
                    $adjustments[] = [
                        'product_id'   => $product->id,
                        'nama_produk'  => $product->nama_produk,
                        'original_qty' => $reqQty,
                        'adjusted_qty' => 0,
                        'status'       => 'unavailable',
                        'keterangan'   => 'Produk sedang tidak tersedia atau stok habis (dilewati).',
                    ];
                } elseif ($reqQty > $availStock) {
                    // Aturan B: Jika pesanan melebihi stok, sesuaikan dengan stok yang tersedia
                    $allUnavailable = false;
                    $adjustments[] = [
                        'product_id'   => $product->id,
                        'nama_produk'  => $product->nama_produk,
                        'original_qty' => $reqQty,
                        'adjusted_qty' => $availStock,
                        'status'       => 'adjusted',
                        'keterangan'   => "Dipesan {$reqQty}, disesuaikan menjadi {$availStock} unit sesuai sisa stok.",
                    ];
                    $itemsToProcess[$product->id] = [
                        'product' => $product,
                        'qty'     => $availStock,
                        'item'    => $agg['raw_item'],
                    ];
                } else {
                    // Stok mencukupi
                    $allUnavailable = false;
                    $itemsToProcess[$product->id] = [
                        'product' => $product,
                        'qty'     => $reqQty,
                        'item'    => $agg['raw_item'],
                    ];
                }
            }

            // Aturan C: Jika seluruh produk tidak tersedia, batalkan checkout dan jangan buat transaksi kosong
            if ($allUnavailable || empty($itemsToProcess)) {
                return [
                    'status'  => 'all_unavailable',
                    'message' => 'Maaf, seluruh produk yang Anda pilih saat ini sedang tidak tersedia atau stok habis. Transaksi tidak dapat diproses.',
                ];
            }

            // Aturan B & D: Jika terdapat penyesuaian stok dan customer belum mengonfirmasi perubahan,
            // kembalikan detail penyesuaian untuk pop-up konfirmasi tanpa menyimpan transaksi
            if (!empty($adjustments) && !$isConfirmed) {
                $adjustedItemsForClient = [];
                foreach ($itemsToProcess as $pId => $proc) {
                    $p = $proc['product'];
                    $adjustedItemsForClient[] = [
                        'id'         => $p->id,
                        'nama'       => $p->nama_produk,
                        'jumlah'     => $proc['qty'],
                        'harga'      => (int)$p->harga_sewa,
                        'gambar'     => asset('images/' . ($p->foto_produk ?: 'kebaya-coco.png')),
                        'kategori'   => $p->kategori?->nama_kategori ?? 'Busana',
                        'keterangan' => $p->desk_produk ?? '',
                        'ukuran'     => $p->ukuran,
                        'durasi'     => $proc['item']['durasi'] ?? 3,
                    ];
                }

                return [
                    'status'         => 'needs_confirmation',
                    'message'        => 'Terdapat penyesuaian jumlah pesanan sesuai ketersediaan stok.',
                    'adjustments'    => $adjustments,
                    'adjusted_items' => $adjustedItemsForClient,
                ];
            }

            // Aturan E: Satu kode_pesanan unik yang sama untuk seluruh item dalam satu checkout
            $kodePesanan = 'CR-INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $totalSubtotal = 0;
            $totalDeposit = 0;
            $orderListText = [];
            $createdOrders = [];

            foreach ($itemsToProcess as $pId => $proc) {
                $product = $proc['product'];
                $finalQty = $proc['qty'];

                // Harga selalu diambil dari database
                $hargaSewa = (int)$product->harga_sewa;
                $subtotalProduk = $hargaSewa * $finalQty;
                $depositProduk  = $depositPerProduk * $finalQty;
                $orderTotal     = $subtotalProduk + $depositProduk;

                $totalSubtotal += $subtotalProduk;
                $totalDeposit  += $depositProduk;

                // Cari atau buat basket baru, pastikan mengecualikan record duplikat historis (ID 4-9)
                $basket = Basket::where('user_id', $user->id)
                    ->where('produk_id', $product->id)
                    ->whereDoesntHave('orders')
                    ->whereNotIn('id', [4, 5, 6, 7, 8, 9])
                    ->first();

                if ($basket) {
                    $basket->update([
                        'jumlah'        => $finalQty,
                        'tanggal_acara' => $request->tanggal_acara,
                    ]);
                } else {
                    $basket = Basket::create([
                        'user_id'       => $user->id,
                        'produk_id'     => $product->id,
                        'jumlah'        => $finalQty,
                        'tanggal_acara' => $request->tanggal_acara,
                    ]);
                }

                // Tepat satu baris pesanan per produk (Aturan A)
                $order = Order::create([
                    'kode_pesanan'   => $kodePesanan,
                    'basket_id'      => $basket->id,
                    'no_whatsapp'    => $request->no_whatsapp,
                    'instagram'      => $request->instagram ?: '-',
                    'domisili'       => $request->domisili,
                    'desk_pemesanan' => Str::limit($product->nama_produk . ' (Acara: ' . $request->tanggal_acara . ')', 150, ''),
                    'total_harga'    => $orderTotal,
                    'status_pesan'   => 'pending',
                    'tb_bb'          => $request->tb_bb,
                ]);

                $createdOrders[] = $order;
                $orderListText[] = "- {$product->nama_produk} x{$finalQty} (Rp " . number_format($hargaSewa, 0, ',', '.') . ")";
            }

            return [
                'status'         => 'success',
                'kode_pesanan'   => $kodePesanan,
                'created_orders' => $createdOrders,
                'total_subtotal' => $totalSubtotal,
                'total_deposit'  => $totalDeposit,
                'grand_total'    => $totalSubtotal + $totalDeposit,
                'order_list_text'=> $orderListText,
            ];
        });

        // Penanganan hasil evaluasi checkout
        if ($result['status'] === 'all_unavailable') {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'         => false,
                    'all_unavailable' => true,
                    'message'         => $result['message'],
                ], 200);
            }
            return back()->withErrors(['items' => $result['message']]);
        }

        if ($result['status'] === 'needs_confirmation') {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'            => false,
                    'needs_confirmation' => true,
                    'message'            => $result['message'],
                    'adjustments'        => $result['adjustments'],
                    'adjusted_items'     => $result['adjusted_items'],
                ], 200);
            }
            return back()->with('warning', $result['message']);
        }

        // Checkout sukses
        $kodePesanan = $result['kode_pesanan'];
        $createdOrders = $result['created_orders'];
        $totalSubtotal = $result['total_subtotal'];
        $totalDeposit = $result['total_deposit'];
        $grandTotal = $result['grand_total'];
        $orderListText = $result['order_list_text'];

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
            'status_pesan' => 'required|in:pending,dikonfirmasi,sedang_dipinjam,selesai,dibatalkan',
        ]);

        $order = Order::with('basket.product')->findOrFail($id);
        $oldStatus = $order->status_pesan;
        $newStatus = $request->status_pesan;

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Status pesanan tidak berubah.');
        }

        $product = $order->basket?->product;
        $qty = $order->basket?->jumlah ?? 1;

        $holdingStockStatuses = ['dikonfirmasi', 'sedang_dipinjam'];
        $wasHoldingStock = in_array($oldStatus, $holdingStockStatuses);
        $willHoldStock = in_array($newStatus, $holdingStockStatuses);

        // 1. Jika berpindah ke status yang meminjam/menahan stok (dikonfirmasi/sedang_dipinjam) dari status bebas
        if (!$wasHoldingStock && $willHoldStock) {
            if ($product) {
                if ($product->stok < $qty) {
                    return back()->with('error', "Gagal ubah status: Stok '{$product->nama_produk}' tidak mencukupi (sisa: {$product->stok}).");
                }
                $product->decrement('stok', $qty);
                if ($product->fresh()->stok <= 0) {
                    $product->update(['status_produk' => 'tidak_tersedia']);
                }
            }
        }

        // 2. Jika pesanan selesai atau dibatalkan setelah sebelumnya meminjam stok, kembalikan stok
        if ($wasHoldingStock && !$willHoldStock) {
            if ($product) {
                $product->increment('stok', $qty);
                if ($product->fresh()->stok > 0 && $product->status_produk === 'tidak_tersedia') {
                    $product->update(['status_produk' => 'tersedia']);
                }
            }
        }

        $order->status_pesan = $newStatus;
        $order->save();

        $labelStatus = [
            'pending'         => 'Menunggu',
            'dikonfirmasi'    => 'Dikonfirmasi',
            'sedang_dipinjam' => 'Sedang Dipinjam',
            'selesai'         => 'Selesai',
            'dibatalkan'      => 'Dibatalkan',
        ][$newStatus] ?? ucfirst($newStatus);

        return back()->with('success', "Status pesanan #{$order->id} berhasil diupdate menjadi '{$labelStatus}'!");
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
