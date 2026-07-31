<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Yönetici guard'ını kontrol et
        if (!Auth::guard('yonetici')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Lütfen Giriş Yapınız'], 401);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
} 