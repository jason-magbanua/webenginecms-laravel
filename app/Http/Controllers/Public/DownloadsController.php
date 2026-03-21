<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\Download;

class DownloadsController extends Controller
{
    public function index()
    {
        $grouped = Download::orderByDesc('download_date')->get()->groupBy('download_type');

        return view('public.downloads', [
            'clients' => $grouped->get(1, collect()),
            'patches' => $grouped->get(2, collect()),
            'tools'   => $grouped->get(3, collect()),
        ]);
    }
}
