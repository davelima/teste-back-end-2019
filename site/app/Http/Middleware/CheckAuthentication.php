<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class CheckAuthentication extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param mixed ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        /**
         * @var \Tymon\JWTAuth\Facades\JWTAuth $token
         */
        $token = JWTAuth::parseToken();

        if (! $token->check(true)) {
            return responder()
                ->error(403, 'Acesso não autorizado')
                ->respond(403);
        }

        return $next($request);
    }
}
