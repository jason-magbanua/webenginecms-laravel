<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class DonationController extends Controller
{
    public function index()
    {
        return view('public.donation');
    }
}
