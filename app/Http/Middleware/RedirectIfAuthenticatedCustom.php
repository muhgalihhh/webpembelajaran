<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedCustom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika user sudah login, arahkan ke dashboard yang sesuai rolenya
                if (Auth::user()->hasRole('admin')) {
                    return redirect()->route('admin.index');
                } elseif (Auth::user()->hasRole('guru')) {
                    return redirect()->route('teacher.index'); // Sesuaikan dengan nama rute guru Anda
                } elseif (Auth::user()->hasRole('siswa')) {
                    return redirect()->route('student.index'); // Sesuaikan dengan nama rute siswa Anda
                }
                // Fallback jika role tidak ditemukan atau tidak cocok, arahkan ke dashboard umum
                return redirect('/');
            }
        }
        return $next($request);
    }
}
