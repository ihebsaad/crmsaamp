<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Activitylog\Models\Activity;

class LogUserActions
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $response = $next($request);

        if ($user) {
            activity()
                ->causedBy($user)
                ->log($request->fullUrl());
        }

        return $response;
    }
}
