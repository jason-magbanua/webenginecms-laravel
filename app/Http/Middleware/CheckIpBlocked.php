<?php

namespace App\Http\Middleware;

use App\Models\WebEngine\BlockedIp;
use Closure;
use Illuminate\Http\Request;

class CheckIpBlocked
{
    public function handle(Request $request, Closure $next)
    {
        if (BlockedIp::where('ip_address', $request->ip())->exists()) {
            abort(403, 'Your IP address has been blocked.');
        }

        return $next($request);
    }
}
