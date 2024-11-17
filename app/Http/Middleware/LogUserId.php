<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogUserId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            // Ajouter l'ID de l'utilisateur au contexte des logs
            Log::withContext(['user_id' => auth()->user()->id]);
        }

        return $next($request);
    }
}
