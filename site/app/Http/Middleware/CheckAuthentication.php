<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

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
        if (! auth()->check()) {
            return responder()
                ->error(403, 'Acesso não autorizado')
                ->respond(403);
        }

        return $next($request);
    }
}
