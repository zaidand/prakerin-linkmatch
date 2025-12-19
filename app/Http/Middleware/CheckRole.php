<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Cek status aktif
        if ($user->status !== 'active') {
            auth()->logout();
            return redirect()
                ->route('login')
                ->withErrors('Akun Anda belum aktif atau ditolak.');
        }

        // Cek role
        if (!in_array($user->role->name, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
