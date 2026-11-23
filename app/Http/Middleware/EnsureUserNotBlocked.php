<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotBlocked
{
    /**
     * Handle an incoming request.
     * Mengatasi BUG-011: Membatalkan sesi secara real-time jika akun pengguna diblokir admin.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->status === 'diblokir') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'Akun Anda telah dinonaktifkan/diblokir oleh administrator.',
                    ], 403);
                }

                return redirect()->route('login')->withErrors([
                    'login' => 'Akun Anda telah dinonaktifkan/diblokir oleh administrator. Silakan hubungi admin.',
                ]);
            }
        }

        return $next($request);
    }
}
