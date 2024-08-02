<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        {
            $user = Auth::user();
            if ($user && !$user->password_changed) {
                return response()->json(['error' => 'You must change your default password before accessing this resource.'], 403);
            }
            return $next($request);
        }
    }
}
