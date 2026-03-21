<?php

namespace App\Http\Controllers\UserCP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function myAccount()
    {
        return view('usercp.myaccount');
    }

    public function myPassword()
    {
        return view('usercp.mypassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:4|max:10|confirmed',
        ]);

        $account = Auth::user();

        if ($account->memb__pwd !== $request->current_password) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        DB::connection('memuonline')
            ->table('MEMB_INFO')
            ->where('memb___id', $account->memb___id)
            ->update(['memb__pwd' => $request->new_password]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function myEmail()
    {
        return view('usercp.myemail');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|max:50',
        ]);

        $account = Auth::user();
        $email = $request->new_email;

        $exists = DB::connection('memuonline')
            ->table('MEMB_INFO')
            ->where('mail_addr', $email)
            ->where('memb___id', '!=', $account->memb___id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['new_email' => 'That email address is already in use.']);
        }

        DB::connection('memuonline')
            ->table('MEMB_INFO')
            ->where('memb___id', $account->memb___id)
            ->update(['mail_addr' => $email]);

        return back()->with('success', 'Email address updated successfully.');
    }
}
