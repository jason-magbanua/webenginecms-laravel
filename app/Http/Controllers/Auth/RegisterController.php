<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('usercp.dashboard');
        }
        if (!config('webengine.features.registration')) {
            abort(403, 'Registration is currently disabled.');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (!config('webengine.features.registration')) {
            abort(403, 'Registration is currently disabled.');
        }

        $request->validate([
            'username' => 'required|alpha_num|min:4|max:10',
            'password' => 'required|min:4|max:10|confirmed',
            'email'    => 'required|email|max:50',
        ]);

        $username = strtolower($request->username);
        $email    = $request->email;
        $password = $request->password;

        // Check if username already exists
        $exists = DB::connection('memuonline')
            ->table('MEMB_INFO')
            ->where('memb___id', $username)
            ->exists();

        if ($exists) {
            return back()->withErrors(['username' => 'That username is already taken.'])->withInput($request->only('username', 'email'));
        }

        // Check if email already exists
        $emailExists = DB::connection('memuonline')
            ->table('MEMB_INFO')
            ->where('mail_addr', $email)
            ->exists();

        if ($emailExists) {
            return back()->withErrors(['email' => 'That email address is already registered.'])->withInput($request->only('username', 'email'));
        }

        DB::connection('memuonline')->table('MEMB_INFO')->insert([
            'memb___id'          => $username,
            'memb__pwd'          => $password,
            'memb_name'          => $username,
            'mail_addr'          => $email,
            'bloc_code'          => 0,
            'ctl_cod'            => 0,
            'AccountLevel'       => 0,
            'AccountExpireDate'  => '9999-12-31',
            'ServerName'         => '',
            'ConnectStat'        => 0,
            'OnlineHours'        => 0,
            'regist_datetime'    => now(),
            'IP'                 => $request->ip(),
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully. You can now log in.');
    }
}
