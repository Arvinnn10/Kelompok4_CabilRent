<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin')
            ->withCount('orders')
            ->withSum('orders', 'total_harga')
            ->with(['orders.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $totalUsers = User::where('role', '!=', 'admin')->count();

        return view('pengguna', compact('users', 'totalUsers'));
    }

    /**
     * Memblokir atau mengaktifkan kembali user dengan proteksi diri sendiri dan admin lain (BUG-012).
     */
    public function toggleBlock($id)
    {
        $targetUser = User::findOrFail($id);

        // Cegah memblokir diri sendiri (BUG-012)
        if ($targetUser->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat memblokir akun Anda sendiri!');
        }

        // Cegah memblokir akun administrator (BUG-012)
        if ($targetUser->isAdmin() || $targetUser->role === 'admin') {
            return back()->with('error', 'Tidak diizinkan memblokir akun sesama administrator!');
        }

        $targetUser->status = ($targetUser->status === 'diblokir') ? 'aktif' : 'diblokir';
        $targetUser->save();

        $pesan = ($targetUser->status === 'diblokir')
            ? "Pengguna {$targetUser->nama_lengkap} berhasil diblokir!"
            : "Pengguna {$targetUser->nama_lengkap} berhasil diaktifkan kembali!";

        return back()->with('success', $pesan);
    }
}
