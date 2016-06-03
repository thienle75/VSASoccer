<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class CaptainPrivileges
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
        if(Auth::user()->teamCaptain())
        {
            return $next($request);
        }

        $errors = new MessageBag(['errors' => 'Access Denied']);

        return redirect('seasons')->with(['errors' => $errors]);

    }
}
