<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecureStorageAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Check jika mengakses storage/materi
        if ($request->is('storage/materi/*')) {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
