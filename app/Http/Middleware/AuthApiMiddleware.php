<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken(); // Laravel умеет автоматически доставать Bearer токен

        if (!$token || $token !== config('app.api_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Можно добавить пользователя в контекст запроса, если нужно
        // $request->attributes->set('user', $user);

        return $next($request);
    }
}
