<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\PassChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $account = DB::connection('memuonline')
            ->table('MEMB_INFO')
            ->where('mail_addr', $request->email)
            ->first();

        if (!$account) {
            return back()->with('success', 'If that email is registered, a reset link has been sent.');
        }

        PassChangeRequest::where('request_account', $account->memb___id)->delete();

        $token = Str::random(40);
        PassChangeRequest::create([
            'request_account' => $account->memb___id,
            'request_token'   => $token,
            'request_date'    => now(),
            'request_expire'  => now()->addHours(2),
        ]);

        $resetUrl = route('password.reset', ['token' => $token]);

        try {
            Mail::raw(
                "Hello {$account->memb___id},\n\nReset your password:\n{$resetUrl}\n\nExpires in 2 hours.",
                fn($m) => $m->to($request->email)->subject('Password Reset')
            );
        } catch (\Exception $e) {}

        return back()->with('success', 'If that email is registered, a reset link has been sent.');
    }

    public function showResetForm(string $token)
    {
        $req = PassChangeRequest::where('request_token', $token)
            ->where('request_expire', '>', now())
            ->first();

        if (!$req) {
            return redirect()->route('password.request')->withErrors(['token' => 'This link is invalid or expired.']);
        }

        return view('auth.reset-password', compact('token'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'password' => 'required|string|min:4|max:10|confirmed',
        ]);

        $req = PassChangeRequest::where('request_token', $request->token)
            ->where('request_expire', '>', now())
            ->first();

        if (!$req) {
            return back()->withErrors(['token' => 'This link is invalid or expired.']);
        }

        DB::connection('memuonline')
            ->table('MEMB_INFO')
            ->where('memb___id', $req->request_account)
            ->update(['memb__pwd' => $request->password]);

        $req->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully. You can now log in.');
    }
}
