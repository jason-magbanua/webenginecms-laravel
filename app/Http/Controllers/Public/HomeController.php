<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use App\Models\WebEngine\News;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $news = News::orderByDesc('news_date')->limit(7)->get();

        $excluded      = config('webengine.rankings.excluded_chars', []);
        $excludedGuilds = config('webengine.rankings.excluded_guilds', []);

        $topLevel = Cache::remember('rankings_level', 300, fn() =>
            Character::select('Name', 'Class', 'cLevel', 'RESETS', 'MapNumber')
                ->when($excluded, fn($q) => $q->whereNotIn('Name', $excluded))
                ->orderByDesc('cLevel')->orderByDesc('RESETS')
                ->limit(10)->get()
        );

        $topGuilds = Cache::remember('rankings_guilds', 300, fn() =>
            Guild::select('G_Name', 'G_Master', 'G_Score', 'G_Mark')
                ->when($excludedGuilds, fn($q) => $q->whereNotIn('G_Name', $excludedGuilds))
                ->orderByDesc('G_Score')
                ->limit(10)->get()
        );

        return view('public.home', compact('news', 'topLevel', 'topGuilds'));
    }
}
