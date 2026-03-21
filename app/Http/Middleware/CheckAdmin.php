<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $admins = config('webengine.admin_usernames', []);

        if (!in_array(Auth::user()->memb___id, $admins)) {
            abort(403);
        }

        return $next($request);
    }
}
