<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuth
{
    /**
     * 簡易Basic認証ミドルウェア
     */
    public function handle(Request $request, Closure $next)
    {
        $username = env('ADMIN_USERNAME', 'admin');
        $password = env('ADMIN_PASSWORD', 'password');

        if ($request->getUser() !== $username || $request->getPassword() !== $password) {
            return response('Unauthorized.', 401, [
                'WWW-Authenticate' => 'Basic realm="Admin Area"'
            ]);
        }

        return $next($request);
    }
}