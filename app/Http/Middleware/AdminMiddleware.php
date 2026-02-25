<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $user = auth()->user();

        if ($user->is_banned) {
            abort(403, 'Votre compte est désactivé.');
        }

        if ($user->role !== 'admin') {
            abort(403);
        }

        return $next($request);
    }
}