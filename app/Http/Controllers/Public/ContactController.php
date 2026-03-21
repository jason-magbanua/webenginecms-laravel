<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'contact_email'   => ['required', 'email'],
            'contact_message' => ['required', 'min:10', 'max:300'],
        ]);

        $to      = config('mail.contact_address', config('mail.from.address'));
        $subject = config('webengine.server_name') . ' - Contact Form';

        Mail::raw($request->contact_message, function ($msg) use ($request, $to, $subject) {
            $msg->to($to)->replyTo($request->contact_email)->subject($subject);
        });

        return back()->with('success', 'Your message has been sent.');
    }
}
