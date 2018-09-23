<?php

namespace App\Http\Middleware;

use Closure;
use App\Auth;

class APIAuthenticate
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
        $auth = new Auth;
        if (!$request->header('token') || !$auth->authenticate($request->header('token'))) {
            return response()->json([
                'error' => 'Authentication has failed'
            ], 401);
        }
        return $next($request);
    }
}
