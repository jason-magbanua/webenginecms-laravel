<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class DonationController extends Controller
{
    public function index()
    {
        $cfg = config('webengine.paypal');

        return view('public.donation', [
            'paypalAction'   => $cfg['sandbox']
                ? 'https://www.sandbox.paypal.com/cgi-bin/webscr'
                : 'https://www.paypal.com/cgi-bin/webscr',
            'paypalEmail'    => $cfg['email'],
            'currency'       => $cfg['currency'],
            'itemName'       => $cfg['item_name'],
            'returnUrl'      => $cfg['return_url'] ?: url('/donation'),
            'notifyUrl'      => url('/api/paypal/ipn'),
            'conversionRate' => $cfg['conversion_rate'],
        ]);
    }
}
