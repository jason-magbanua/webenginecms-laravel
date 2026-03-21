<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game\Character;
use App\Models\WebEngine\Ban;
use App\Models\WebEngine\News;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_accounts'    => DB::connection('memuonline')->table('MEMB_INFO')->count(),
            'total_characters'  => Character::count(),
            'total_news'        => News::count(),
            'active_bans'       => Ban::active()->count(),
            'online_characters' => count(Cache::get('online_characters', [])),
        ];

        return view('admin.dashboard', [
            'stats'           => $stats,
            'php_version'     => PHP_VERSION,
            'laravel_version' => app()->version(),
            'os'              => PHP_OS,
        ]);
    }
}
