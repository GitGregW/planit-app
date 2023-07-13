<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUserGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $user_group): Response
    {
        if(!$request->user() || $request->user()->userGroup()->first()->name != $user_group)
        {
            return redirect('/');
        }
        return $next($request);
    }
}
