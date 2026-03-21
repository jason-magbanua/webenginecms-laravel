<?php

namespace App\Http\Middleware;

use App\Models\WebEngine\Ban;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $banned = Ban::where('ban_account', Auth::user()->memb___id)
                ->active()
                ->exists();

            if ($banned) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['account' => 'Your account has been banned.']);
            }
        }

        return $next($request);
    }
}
