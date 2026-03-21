<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\FailedLoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('usercp.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $ip = $request->ip();
        $brute = config('webengine.brute_force');

        // Check brute force lockout
        $attempt = FailedLoginAttempt::where('fla_ip', $ip)->first();
        if ($attempt && $attempt->fla_locked_until && now()->lessThan($attempt->fla_locked_until)) {
            return back()->withErrors(['username' => 'Too many failed attempts. Please try again later.']);
        }

        if (Auth::attempt(['memb___id' => $request->username, 'password' => $request->password])) {
            // Clear failed attempts on success
            if ($attempt) {
                $attempt->delete();
            }
            $request->session()->regenerate();
            return redirect()->intended(route('usercp.dashboard'));
        }

        // Record failed attempt
        if ($attempt) {
            $attempt->fla_attempts++;
            $attempt->fla_last_attempt = now();
            if ($attempt->fla_attempts >= $brute['max_attempts']) {
                $attempt->fla_locked_until = now()->addMinutes($brute['lockout_minutes']);
            }
            $attempt->save();
        } else {
            FailedLoginAttempt::create([
                'fla_ip'           => $ip,
                'fla_attempts'     => 1,
                'fla_last_attempt' => now(),
                'fla_locked_until' => null,
            ]);
        }

        return back()->withErrors(['username' => 'Invalid username or password.'])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
