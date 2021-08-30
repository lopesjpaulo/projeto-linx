<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class AuthenticateAccess
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
        $validKeys = explode(',', env('ACCEPTED_KEYS'));

        if (in_array($request->header('Authorization'), $validKeys)) {
            return $next($request);
        }

        abort(Response::HTTP_UNAUTHORIZED);
    }
}
