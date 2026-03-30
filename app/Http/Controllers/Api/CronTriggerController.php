<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronTriggerController extends Controller
{
    public function run(Request $request)
    {
        $token = config('webengine.cron_token');

        if ($token && $request->query('token') !== $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Artisan::call('cron:dispatch');

        return response()->json(['status' => 'ok', 'output' => Artisan::output()]);
    }
}
