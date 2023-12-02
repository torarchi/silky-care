<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->verification) {
            return response()->json(['error' => 'Пользователь уже верифицирован.'], 422);
        }

        if (!$verificationCode || $user->finished) {
            return response()->json(['error' => 'Неверный код или код истек.'], 422);
        }

        return $next($request);
    }
    
}
