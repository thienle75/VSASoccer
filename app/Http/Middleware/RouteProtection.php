<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Support\MessageBag;

class RouteProtection
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
        if(!Auth::user()->authorized()) {
            $errors = new MessageBag([
                'errors' => 'Access Denied'
            ]);
            return redirect()->back()->with([
                'errors' => $errors
            ]);
        }

        return $next($request);
    }
}
